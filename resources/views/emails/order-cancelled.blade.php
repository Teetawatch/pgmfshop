@extends('emails.layouts.base')

@section('title', 'คำสั่งซื้อถูกยกเลิก')
@section('header-subtitle', 'แจ้งยกเลิกคำสั่งซื้อ')

@section('content')
    <p class="greeting">สวัสดีคุณ {{ $order->user->name }}</p>

    <p class="text">
        คำสั่งซื้อ <strong>#{{ $order->order_number }}</strong> ของคุณถูกยกเลิกแล้ว
    </p>

    <div class="highlight-box danger">
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 24px;">❌</span>
            <div>
                <p style="font-size: 16px; color: #991b1b; margin: 0; font-weight: 600;">
                    คำสั่งซื้อถูกยกเลิก
                </p>
                <p style="font-size: 14px; color: #991b1b; margin: 4px 0 0;">
                    หมายเลขคำสั่งซื้อ #{{ $order->order_number }}
                </p>
            </div>
        </div>
    </div>

    {{-- Reason --}}
    @if($reason)
    <h3 style="font-size: 15px; color: #1a1a2e; margin: 20px 0 10px;">📝 เหตุผล</h3>
    <div class="info-box">
        <p style="font-size: 14px; color: #4a5568; margin: 0;">
            {{ $reason }}
        </p>
    </div>
    @endif

    {{-- Order Summary --}}
    <div class="info-box">
        <table style="width: 100%; font-size: 14px;">
            <tr>
                <td style="padding: 6px 0; color: #718096;">หมายเลขคำสั่งซื้อ</td>
                <td style="padding: 6px 0; text-align: right; color: #2d3748; font-weight: 600;">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #718096;">ยอดรวม</td>
                <td style="padding: 6px 0; text-align: right; color: #2d3748; font-weight: 600;">฿{{ number_format($order->total, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #718096;">สถานะ</td>
                <td style="padding: 6px 0; text-align: right;">
                    <span class="badge badge-danger">ยกเลิกแล้ว</span>
                </td>
            </tr>
        </table>
    </div>

    {{-- Items --}}
    <h3 style="font-size: 15px; color: #1a1a2e; margin: 20px 0 10px;">🛒 รายการสินค้า</h3>
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

    @if($order->payment_method === 'promptpay' && $order->slip_verified)
    <div class="highlight-box warning">
        <p style="font-size: 14px; color: #92400e; margin: 0;">
            💰 <strong>การคืนเงิน:</strong> หากคุณชำระเงินแล้ว ทางร้านจะดำเนินการคืนเงินให้ภายใน 3-5 วันทำการ
        </p>
    </div>
    @endif

    <p style="text-align: center;">
        <a href="{{ route('products') }}" class="btn">🛍️ เลือกซื้อสินค้าอื่น</a>
    </p>

    <hr class="divider">

    <p class="text" style="font-size: 13px; color: #a0aec0;">
        หากมีข้อสงสัย กรุณาติดต่อเราพร้อมแจ้งหมายเลขคำสั่งซื้อ
    </p>
@endsection
