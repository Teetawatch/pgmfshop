<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items');

        if (!$request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($orders);
    }

    public function show(Request $request, string $orderNumber)
    {
        $order = Order::with(['items', 'user'])->where('order_number', $orderNumber)->firstOrFail();

        if (!$request->user()->isAdmin() && $order->user_id !== $request->user()->id) {
            abort(403, 'ไม่มีสิทธิ์เข้าถึง');
        }

        return response()->json($order);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|array',
            'shipping_address.name' => 'required|string',
            'shipping_address.phone' => 'required|string',
            'shipping_address.address' => 'required|string',
            'shipping_address.district' => 'required|string',
            'shipping_address.province' => 'required|string',
            'shipping_address.postalCode' => 'required|string',
            'payment_method' => 'required|string',
            'shipping_method' => 'required|string',
            'coupon_code' => 'nullable|string',
        ]);

        $subtotal = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);

            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'message' => "สินค้า \"{$product->name}\" มีสต็อกไม่เพียงพอ (เหลือ {$product->stock} ชิ้น)",
                ], 422);
            }

            $itemTotal = $product->price * $item['quantity'];
            $subtotal += $itemTotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_image' => $product->images[0] ?? null,
                'price' => $product->price,
                'quantity' => $item['quantity'],
                'total' => $itemTotal,
            ];
        }

        $discount = 0;
        $couponCode = null;
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
                $couponCode = $coupon->code;
                $coupon->increment('used_count');
            }
        }

        $shippingCost = $this->calculateShipping($request->shipping_method);
        $total = $subtotal - $discount + $shippingCost;

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $request->user()->id,
            'status' => 'awaiting_payment',
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'coupon_code' => $couponCode,
            'payment_method' => $request->payment_method,
            'shipping_method' => $request->shipping_method,
            'shipping_address' => $request->shipping_address,
            'payment_deadline' => now()->addHours(24),
            'status_history' => [
                [
                    'status' => 'awaiting_payment',
                    'note' => 'สร้างคำสั่งซื้อแล้ว รอการชำระเงิน',
                    'timestamp' => now()->toISOString(),
                ],
            ],
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);

            Product::where('id', $item['product_id'])->update([
                'stock' => \DB::raw("stock - {$item['quantity']}"),
                'sold' => \DB::raw("sold + {$item['quantity']}"),
            ]);
        }

        return response()->json($order->load('items'), 201);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,awaiting_payment,paid,processing,shipped,delivered,cancelled',
            'note' => 'nullable|string',
            'tracking_number' => 'nullable|string',
            'tracking_url' => 'nullable|string',
        ]);

        if ($request->filled('tracking_number')) {
            $order->tracking_number = $request->tracking_number;
            $order->tracking_url = $request->tracking_url;
        }

        $order->addStatusHistory($request->status, $request->note ?? '');

        if ($request->status === 'cancelled') {
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->update([
                    'stock' => \DB::raw("stock + {$item->quantity}"),
                    'sold' => \DB::raw("sold - {$item->quantity}"),
                ]);
            }
        }

        return response()->json($order->load('items'));
    }

    private function calculateShipping(string $method): float
    {
        return match ($method) {
            'flash' => 50,
            'kerry' => 60,
            'thaipost' => 35,
            'free' => 0,
            default => 50,
        };
    }
}
