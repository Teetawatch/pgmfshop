@extends('emails.layouts.base')

@section('title', 'ยืนยันคำสั่งซื้อ')
@section('header-subtitle', 'คำสั่งซื้อของคุณได้รับแล้ว')

@section('content')
    <p class="greeting">สวัสดีคุณ {{ $order->user->name }}</p>

    <p class="text">
        ขอบคุณสำหรับคำสั่งซื้อ! เราได้รับคำสั่งซื้อของคุณเรียบร้อยแล้ว
    </p>

    <div class="highlight-box info">
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 24px;">📋</span>
            <div>
                <p style="font-size: 16px; color: #1e40af; margin: 0; font-weight: 600;">
                    หมายเลขคำสั่งซื้อ: {{ $order->order_number }}
                </p>
                <p style="font-size: 14px; color: #1e40af; margin: 4px 0 0;">
                    เราได้รับคำสั่งซื้อของคุณเรียบร้อยแล้ว
                </p>
            </div>
        </div>
    </div>

    {{-- Order Items --}}
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

    {{-- Summary --}}
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
                <td style="padding: 10px 0; font-weight: 700; color: #1a1a2e; font-size: 16px;">ยอดรวมทั้งสิ้น</td>
                <td style="padding: 10px 0; text-align: right; font-weight: 700; color: #1a1a2e; font-size: 16px;">฿{{ number_format($order->total, 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- Shipping Address --}}
    @if($order->shipping_address)
    <h3 style="font-size: 15px; color: #1a1a2e; margin: 20px 0 10px;">📦 ที่อยู่จัดส่ง</h3>
    <div class="info-box">
        <p style="font-size: 14px; color: #4a5568; margin: 0; line-height: 1.8;">
            <strong>{{ $order->shipping_address['name'] ?? '' }}</strong><br>
            {{ $order->shipping_address['phone'] ?? '' }}<br>
            {{ $order->shipping_address['address'] ?? '' }}
            {{ $order->shipping_address['district'] ?? '' }}
            {{ $order->shipping_address['province'] ?? '' }}
            {{ $order->shipping_address['postal_code'] ?? '' }}
        </p>
    </div>
    @endif

    {{-- Payment Info --}}
    <div class="info-box">
        <table style="width: 100%; font-size: 14px;">
            <tr>
                <td style="padding: 6px 0; color: #718096;">วิธีชำระเงิน</td>
                <td style="padding: 6px 0; text-align: right; color: #2d3748; font-weight: 600;">
                    {{ $order->payment_method === 'promptpay' ? 'พร้อมเพย์' : $order->payment_method }}
                </td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #718096;">วิธีจัดส่ง</td>
                <td style="padding: 6px 0; text-align: right; color: #2d3748; font-weight: 600;">
                    @php
                        $carriers = ['flash' => 'Flash Express', 'kerry' => 'Kerry Express', 'thaipost' => 'ไปรษณีย์ไทย EMS'];
                    @endphp
                    {{ $carriers[$order->shipping_method] ?? $order->shipping_method }}
                </td>
            </tr>
        </table>
    </div>

    <p style="text-align: center;">
        <a href="{{ url('/account/orders/' . $order->id) }}" class="btn">📋 ดูรายละเอียดคำสั่งซื้อ</a>
    </p>

    <hr class="divider">

    <p class="text" style="font-size: 13px; color: #a0aec0;">
        หากมีข้อสงสัยเกี่ยวกับคำสั่งซื้อ กรุณาติดต่อเราพร้อมแจ้งหมายเลขคำสั่งซื้อ
    </p>
@endsection
