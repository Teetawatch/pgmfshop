<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Mail\PaymentSuccessMail;
use App\Mail\ShippingNotificationMail;
use App\Mail\OrderCancelledMail;
use App\Helpers\ThaiTextHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderManageController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->latest()->paginate(15);

        $statusCounts = [
            'all' => Order::count(),
            'awaiting_payment' => Order::where('status', 'awaiting_payment')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'expired' => Order::where('status', 'expired')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function show(Order $order)
    {
        $order->load(['items', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,awaiting_payment,paid,processing,shipped,delivered,cancelled',
            'note' => 'nullable|string',
            'tracking_number' => 'nullable|string',
        ]);

        if ($request->filled('tracking_number')) {
            $order->tracking_number = $request->tracking_number;
        }

        $order->addStatusHistory($request->status, $request->note ?? '');

        $order->load(['items', 'user']);

        if ($request->status === 'shipped' && $order->user) {
            try {
                Mail::to($order->user->email)->send(new ShippingNotificationMail($order));
            } catch (\Exception $e) {
                \Log::error('Failed to send shipping notification email: ' . $e->getMessage());
            }
        }

        if ($request->status === 'cancelled') {
            $this->restoreOrderStock($order, 'ยกเลิกคำสั่งซื้อ');
            if ($order->user) {
                try {
                    Mail::to($order->user->email)->send(new OrderCancelledMail($order, $request->note ?? 'ยกเลิกโดยผู้ดูแลระบบ'));
                } catch (\Exception $e) {
                    \Log::error('Failed to send order cancellation email: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('admin.orders.show', $order)->with('success', 'อัปเดตสถานะสำเร็จ');
    }

    public function cancel(Request $request, Order $order)
    {
        if (in_array($order->status, ['cancelled', 'delivered'])) {
            return back()->with('error', 'ไม่สามารถยกเลิกคำสั่งซื้อนี้ได้');
        }

        $note = $request->input('cancel_reason', 'ยกเลิกโดยผู้ดูแลระบบ');

        // Restore stock with audit trail
        $order->load('items');
        $this->restoreOrderStock($order, $note);

        $order->addStatusHistory('cancelled', $note);

        $order->load('user');
        if ($order->user) {
            try {
                Mail::to($order->user->email)->send(new OrderCancelledMail($order, $note));
            } catch (\Exception $e) {
                \Log::error('Failed to send order cancellation email: ' . $e->getMessage());
            }
        }

        return back()->with('success', "ยกเลิกคำสั่งซื้อ {$order->order_number} สำเร็จ");
    }

    public function verifySlip(Order $order)
    {
        if ($order->slip_verified) {
            return back()->with('error', 'สลิปนี้ถูกตรวจสอบแล้ว');
        }

        $order->slip_verified = true;
        $order->save();
        $order->addStatusHistory('paid', 'ตรวจสอบสลิปแล้ว - สลิปถูกต้อง');

        $order->load(['items', 'user']);
        if ($order->user) {
            try {
                Mail::to($order->user->email)->send(new PaymentSuccessMail($order));
            } catch (\Exception $e) {
                // Log error but don't fail the process
                \Log::error('Failed to send payment success email: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'ยืนยันสลิปสำเร็จ สถานะเปลี่ยนเป็น ชำระแล้ว');
    }

    public function receipt(Order $order)
    {
        $order->load(['items', 'user']);

        $address = $order->shipping_address;
        $customerAddress = '';
        if ($address) {
            $parts = array_filter([
                $address['address'] ?? '',
                $address['district'] ?? '',
                $address['province'] ?? '',
                $address['postalCode'] ?? '',
            ]);
            $customerAddress = implode(' ', $parts);
        }

        $data = [
            'order' => $order,
            'shopName' => 'มูลนิธิคณะก้าวหน้า',
            'shopAddress' => '167 อาคารอนาคตใหม่ ชั้น 3 ยูนิตที่1<br>แขวงหัวหมาก เขตบางกะปิ กทม. 10240',
            'shopTaxId' => '099-3-00045304-2',
            'receiptDate' => $order->created_at->format('j/n/Y'),
            'receiptNumber' => $order->order_number,
            'customerName' => $address['name'] ?? ($order->user->name ?? '-'),
            'customerAddress' => $customerAddress,
            'customerPhone' => $address['phone'] ?? ($order->user->phone ?? ''),
            'bahtText' => ThaiTextHelper::bahtText((float) $order->total),
        ];

        $pdf = Pdf::loadView('admin.orders.receipt', $data);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('defaultFont', 'THSarabunNew');
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->stream("receipt-{$order->order_number}.pdf");
    }

    public function shippingLabel(Order $order)
    {
        $order->load(['items', 'user']);

        $address = $order->shipping_address;

        // Carrier info
        $carriers = [
            'flash'    => ['name' => 'FLASH EXPRESS', 'icon' => ''],
            'kerry'    => ['name' => 'KERRY EXPRESS', 'icon' => ''],
            'thaipost' => ['name' => 'ไปรษณีย์ไทย EMS', 'icon' => ''],
        ];
        $carrier = $carriers[$order->shipping_method] ?? ['name' => strtoupper($order->shipping_method ?? 'STANDARD'), 'icon' => '📦'];

        $data = [
            'order' => $order,
            'carrierName' => $carrier['name'],
            'carrierIcon' => $carrier['icon'],
            'recipientName' => $address['name'] ?? ($order->user->name ?? '-'),
            'recipientPhone' => $address['phone'] ?? '',
            'recipientAddress' => $address['address'] ?? '',
            'recipientDistrict' => $address['district'] ?? '',
            'recipientProvince' => $address['province'] ?? '',
            'recipientPostalCode' => $address['postal_code'] ?? $address['postalCode'] ?? '',
            'senderName' => 'มูลนิธิคณะก้าวหน้า',
            'senderAddress' => "167 อาคารอนาคตใหม่ ชั้น 3 ยูนิตที่ 1\nแขวงหัวหมาก เขตบางกะปิ กทม. 10240",
            'senderPhone' => '02-123-4567',
        ];

        return view('admin.orders.shipping-label', $data);
    }

    public function rejectSlip(Order $order)
    {
        if (in_array($order->status, ['cancelled', 'delivered'])) {
            return back()->with('error', 'ไม่สามารถปฏิเสธสลิปของคำสั่งซื้อนี้ได้');
        }

        // Restore stock with audit trail
        $order->load('items');
        $this->restoreOrderStock($order, 'สลิปไม่ถูกต้อง');

        $order->addStatusHistory('cancelled', 'สลิปไม่ถูกต้อง - ยกเลิกคำสั่งซื้อ');

        $order->load('user');
        if ($order->user) {
            try {
                Mail::to($order->user->email)->send(new OrderCancelledMail($order, 'สลิปไม่ถูกต้อง - ยกเลิกคำสั่งซื้อ'));
            } catch (\Exception $e) {
                \Log::error('Failed to send slip rejection email: ' . $e->getMessage());
            }
        }

        return back()->with('success', "ปฏิเสธสลิปและยกเลิกคำสั่งซื้อ {$order->order_number} สำเร็จ");
    }

    /**
     * Restore stock for all items in an order (variant-aware).
     */
    private function restoreOrderStock(Order $order, string $reason): void
    {
        foreach ($order->items as $item) {
            if ($item->variant_id) {
                $variant = ProductVariant::find($item->variant_id);
                if ($variant) {
                    $variant->adjustStock($item->quantity, StockMovement::TYPE_RETURN, $reason, StockMovement::REF_ORDER, $order->order_number);
                }
            } else {
                $p = Product::find($item->product_id);
                if ($p) {
                    $p->adjustStock($item->quantity, StockMovement::TYPE_RETURN, $reason, StockMovement::REF_ORDER, $order->order_number);
                }
            }
            Product::where('id', $item->product_id)->decrement('sold', $item->quantity);
        }
    }
}
