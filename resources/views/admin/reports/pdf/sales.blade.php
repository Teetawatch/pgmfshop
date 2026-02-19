@extends('admin.reports.pdf.layout')

@section('title', 'รายงานยอดขาย')
@section('subtitle', 'ช่วงวันที่ ' . $startDate->format('d/m/Y') . ' — ' . $endDate->format('d/m/Y'))

@section('content')
    <table class="summary-grid">
        <tr>
            <td>
                <div class="value">฿{{ number_format($summary['total_revenue'], 0) }}</div>
                <div class="label">ยอดขายรวม</div>
            </td>
            <td>
                <div class="value">{{ number_format($summary['total_orders']) }}</div>
                <div class="label">คำสั่งซื้อ</div>
            </td>
            <td>
                <div class="value">{{ number_format($summary['total_items']) }}</div>
                <div class="label">สินค้าที่ขาย</div>
            </td>
            <td>
                <div class="value">฿{{ number_format($summary['avg_order_value'], 0) }}</div>
                <div class="label">ค่าเฉลี่ยต่อออเดอร์</div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>เลขคำสั่งซื้อ</th>
                <th>วันที่</th>
                <th>ลูกค้า</th>
                <th class="text-center">จำนวน</th>
                <th class="text-right">ราคาสินค้า</th>
                <th class="text-right">ส่วนลด</th>
                <th class="text-right">ค่าส่ง</th>
                <th class="text-right">ยอดรวม</th>
                <th>สถานะ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                @php
                    $statusLabels = [
                        'pending' => 'รอดำเนินการ', 'awaiting_payment' => 'รอชำระเงิน',
                        'paid' => 'ชำระแล้ว', 'processing' => 'กำลังจัดเตรียม',
                        'shipped' => 'จัดส่งแล้ว', 'delivered' => 'ส่งสำเร็จ',
                    ];
                @endphp
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td class="text-center">{{ $order->items->sum('quantity') }}</td>
                    <td class="text-right">฿{{ number_format($order->subtotal, 0) }}</td>
                    <td class="text-right">{{ $order->discount > 0 ? '-฿'.number_format($order->discount, 0) : '-' }}</td>
                    <td class="text-right">฿{{ number_format($order->shipping_cost, 0) }}</td>
                    <td class="text-right"><strong>฿{{ number_format($order->total, 0) }}</strong></td>
                    <td>{{ $statusLabels[$order->status] ?? $order->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
