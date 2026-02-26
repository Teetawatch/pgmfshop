<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\ShippingNotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ShippingDispatchController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])
            ->whereIn('status', ['processing', 'paid']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('tracking_filter')) {
            if ($request->tracking_filter === 'has') {
                $query->whereNotNull('tracking_number')->where('tracking_number', '!=', '');
            } elseif ($request->tracking_filter === 'none') {
                $query->where(function ($q) {
                    $q->whereNull('tracking_number')->orWhere('tracking_number', '');
                });
            }
        }

        $orders = $query->oldest()->paginate(50)->withQueryString();

        $stats = [
            'total'        => Order::whereIn('status', ['processing', 'paid'])->count(),
            'has_tracking' => Order::whereIn('status', ['processing', 'paid'])->whereNotNull('tracking_number')->where('tracking_number', '!=', '')->count(),
            'no_tracking'  => Order::whereIn('status', ['processing', 'paid'])->where(function ($q) { $q->whereNull('tracking_number')->orWhere('tracking_number', ''); })->count(),
        ];

        return view('admin.shipping.dispatch', compact('orders', 'stats'));
    }

    public function assignTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:100',
        ]);

        $tracking = trim($request->tracking_number);

        $duplicate = Order::where('tracking_number', $tracking)
            ->where('id', '!=', $order->id)
            ->first();

        if ($duplicate) {
            return response()->json([
                'success' => false,
                'message' => "เลข Tracking นี้ถูกใช้กับคำสั่งซื้อ {$duplicate->order_number} แล้ว",
            ], 422);
        }

        $order->tracking_number = $tracking;
        $order->addStatusHistory('shipped', "อัปเดตเลข Tracking: {$tracking}");

        $order->load(['items', 'user']);
        if ($order->user) {
            try {
                Mail::to($order->user->email)->send(new ShippingNotificationMail($order));
            } catch (\Exception $e) {
                \Log::error('ShippingDispatch: failed to send email: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success'         => true,
            'message'         => "บันทึกสำเร็จ! {$order->order_number}",
            'tracking_number' => $tracking,
            'order_number'    => $order->order_number,
            'label_url'       => route('admin.orders.shippingLabel', $order),
        ]);
    }

    public function bulkLabels(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->route('admin.dispatch.index')->with('error', 'ไม่ได้เลือกคำสั่งซื้อ');
        }

        $orders = Order::with(['items.product', 'user'])
            ->whereIn('id', $ids)
            ->whereNotNull('tracking_number')
            ->where('tracking_number', '!=', '')
            ->get();

        if ($orders->isEmpty()) {
            return redirect()->route('admin.dispatch.index')->with('error', 'ไม่พบคำสั่งซื้อที่มีเลข Tracking');
        }

        $senderName    = 'มูลนิธิคณะก้าวหน้า';
        $senderAddress = "167 อาคารอนาคตใหม่ ชั้น 3 ยูนิตที่ 1\nแขวงหัวหมาก เขตบางกะปิ กทม. 10240";
        $senderPhone   = '02-123-4567';

        return view('admin.shipping.bulk-labels', compact('orders', 'senderName', 'senderAddress', 'senderPhone'));
    }
}
