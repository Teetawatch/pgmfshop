@extends('emails.layouts.base')

@section('title', 'จัดส่งสินค้าแล้ว')
@section('header-subtitle', 'สินค้าของคุณกำลังเดินทาง!')

@section('content')
    <p class="greeting">สวัสดีคุณ {{ $order->user->name }}</p>

    <p class="text">
        สินค้าในคำสั่งซื้อ <strong>#{{ $order->order_number }}</strong> ได้ถูกจัดส่งแล้ว!
    </p>

    <div class="highlight-box success">
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 24px;">🚚</span>
            <div>
                <p style="font-size: 16px; color: #166534; margin: 0; font-weight: 600;">
                    จัดส่งแล้ว!
                </p>
                <p style="font-size: 14px; color: #166534; margin: 4px 0 0;">
                    สินค้ากำลังเดินทางไปหาคุณ
                </p>
            </div>
        </div>
    </div>

    <div style="margin: 20px 0; font-size: 14px; color: #4a5568; line-height: 1.6; text-align: center;">
        <p style="margin: 0;">🙏 ขอบคุณที่สนับสนุนสินค้ามูลนิธิคณะก้าวหน้า</p>
        <p style="margin: 8px 0;">ท่านสามารถตรวจสอบเลขพัสดุได้หลังเวลา 16.00 น. ของวันถัดไป</p>
        <p style="margin: 0;">📦 ทั้งนี้ ระยะเวลาอาจเร็วหรือช้ากว่ากำหนดเล็กน้อย ขึ้นอยู่กับระบบและการจัดส่งในแต่ละพื้นที่</p>
    </div>

    {{-- Tracking Info --}}
    <div class="info-box">
        <table style="width: 100%; font-size: 14px;">
            <tr>
                <td style="padding: 8px 0; color: #718096;">หมายเลขคำสั่งซื้อ</td>
                <td style="padding: 8px 0; text-align: right; color: #2d3748; font-weight: 600;">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #718096;">ขนส่ง</td>
                <td style="padding: 8px 0; text-align: right; color: #2d3748; font-weight: 600;">
                    @php
                        $carriers = ['flash' => 'Flash Express', 'kerry' => 'Kerry Express', 'thaipost' => 'ไปรษณีย์ไทย EMS'];
                    @endphp
                    {{ $carriers[$order->shipping_method] ?? $order->shipping_method }}
                </td>
            </tr>
            @if($order->tracking_number)
            <tr>
                <td style="padding: 8px 0; color: #718096;">เลขพัสดุ (Tracking Number)</td>
                <td style="padding: 8px 0; text-align: right; color: #1a1a2e; font-weight: 700; font-size: 16px; letter-spacing: 1px;">
                    {{ $order->tracking_number }}
                </td>
            </tr>
            @endif
        </table>
    </div>

    {{-- Tracking Link --}}
    @php
        $trackingUrl = $order->tracking_url;
        if (!$trackingUrl && $order->tracking_number) {
            $trackingUrls = [
                'flash' => 'https://flashexpress.com/fle/tracking?se=' . $order->tracking_number,
                'kerry' => 'https://th.kerryexpress.com/th/track/?track=' . $order->tracking_number,
                'thaipost' => 'https://track.thailandpost.co.th/?trackNumber=' . $order->tracking_number,
            ];
            $trackingUrl = $trackingUrls[$order->shipping_method] ?? null;
        }
    @endphp

    @if($trackingUrl)
    <p style="text-align: center;">
        <a href="{{ $trackingUrl }}" class="btn" style="background: #2563eb;">ติดตามพัสดุ</a>
    </p>
    @endif

    {{-- Shipping Address --}}
    @if($order->shipping_address)
    <h3 style="font-size: 15px; color: #1a1a2e; margin: 20px 0 10px;">ที่อยู่จัดส่ง</h3>
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

    {{-- Order Items Summary --}}
    <h3 style="font-size: 15px; color: #1a1a2e; margin: 20px 0 10px;">สินค้าในพัสดุ</h3>
    <table class="items">
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}@if(!empty($item->options))<br><small style="color:#999;">@if(!empty($item->options['size']))ไซส์: {{ $item->options['size'] }}@endif @if(!empty($item->options['color']))สี: {{ $item->options['color'] }}@endif</small>@endif x{{ $item->quantity }}</td>
                <td style="text-align: right;">฿{{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr class="divider">

    <p class="text" style="font-size: 13px; color: #a0aec0;">
        หากมีปัญหาเกี่ยวกับการจัดส่ง กรุณาติดต่อเราพร้อมแจ้งหมายเลขคำสั่งซื้อ
    </p>
@endsection
