@extends('emails.layouts.base')

@section('title', 'ชำระเงินสำเร็จ')
@section('header-subtitle', 'ขอบคุณสำหรับการชำระเงิน')

@section('content')
    <p class="greeting">สวัสดีคุณ {{ $order->user->name }}</p>

    <p class="text">
        เราได้รับการชำระเงินสำหรับคำสั่งซื้อของคุณเรียบร้อยแล้ว
    </p>

    <div class="highlight-box success">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div>
                <p style="font-size: 16px; color: #166534; margin: 0; font-weight: 600;">
                    ชำระเงินสำเร็จ
                </p>
                <p style="font-size: 14px; color: #166534; margin: 4px 0 0;">
                    คำสั่งซื้อ #{{ $order->order_number }}
                </p>
            </div>
        </div>
    </div>

    {{-- Receipt / Summary --}}
    <h3 style="font-size: 15px; color: #1a1a2e; margin: 20px 0 10px;">ใบเสร็จรับเงิน</h3>

    <table class="items">
        <thead>
            <tr>
                <th>สินค้า</th>
                <th style="text-align: center;">จำนวน</th>
                <th style="text-align: right;">ราคา</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}@if(!empty($item->options))<br><small style="color:#999;">@if(!empty($item->options['size']))ไซส์: {{ $item->options['size'] }}@endif @if(!empty($item->options['color']))สี: {{ $item->options['color'] }}@endif</small>@endif</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">฿{{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="info-box">
        <table style="width: 100%; font-size: 14px;">
            <tr>
                <td style="padding: 6px 0; color: #718096;">ราคาสินค้า</td>
                <td style="padding: 6px 0; text-align: right; color: #2d3748;">฿{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->discount > 0)
            <tr>
                <td style="padding: 6px 0; color: #718096;">ส่วนลด</td>
                <td style="padding: 6px 0; text-align: right; color: #e53e3e;">-฿{{ number_format($order->discount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 6px 0; color: #718096;">ค่าจัดส่ง</td>
                <td style="padding: 6px 0; text-align: right; color: #2d3748;">
                    @if($order->shipping_cost > 0)
                        ฿{{ number_format($order->shipping_cost, 2) }}
                    @else
                        <span style="color: #22543d;">ฟรี</span>
                    @endif
                </td>
            </tr>
            <tr style="border-top: 2px solid #e2e8f0;">
                <td style="padding: 10px 0; font-weight: 700; color: #1a1a2e; font-size: 16px;">ยอดชำระทั้งสิ้น</td>
                <td style="padding: 10px 0; text-align: right; font-weight: 700; color: #1a1a2e; font-size: 16px;">฿{{ number_format($order->total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="info-box">
        <table style="width: 100%; font-size: 14px;">
            <tr>
                <td style="padding: 6px 0; color: #718096;">หมายเลขคำสั่งซื้อ</td>
                <td style="padding: 6px 0; text-align: right; color: #2d3748; font-weight: 600;">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #718096;">วิธีชำระเงิน</td>
                <td style="padding: 6px 0; text-align: right; color: #2d3748; font-weight: 600;">
                    {{ $order->payment_method === 'promptpay' ? 'พร้อมเพย์' : $order->payment_method }}
                </td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #718096;">วันที่ชำระ</td>
                <td style="padding: 6px 0; text-align: right; color: #2d3748; font-weight: 600;">{{ now()->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <p class="text">
        สินค้าของคุณกำลังเตรียมจัดส่ง เราจะแจ้งให้ทราบเมื่อจัดส่งแล้ว
    </p>

    <p style="text-align: center;">
        <a href="{{ url('/account/orders/' . $order->id) }}" class="btn">ดูรายละเอียดคำสั่งซื้อ</a>
    </p>
@endsection
