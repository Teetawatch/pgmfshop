@extends('emails.layouts.base')

@section('title', 'ยินดีต้อนรับ!')
@section('header-subtitle', 'ขอบคุณที่สมัครสมาชิก')

@section('content')
    <p class="greeting">สวัสดีคุณ {{ $user->name }} 👋</p>

    <p class="text">
        ยินดีต้อนรับสู่ <strong>{{ config('app.name', 'PGMF Shop') }}</strong>!
        บัญชีของคุณถูกสร้างเรียบร้อยแล้ว
    </p>

    <div class="highlight-box info">
        <p style="font-size: 14px; color: #1e40af; margin: 0;">
            🎉 คุณสามารถเริ่มเลือกซื้อสินค้าได้ทันที
        </p>
    </div>

    <div class="info-box">
        <table style="width: 100%; font-size: 14px;">
            <tr>
                <td style="padding: 6px 0; color: #718096;">ชื่อ</td>
                <td style="padding: 6px 0; text-align: right; font-weight: 600; color: #2d3748;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #718096;">อีเมล</td>
                <td style="padding: 6px 0; text-align: right; font-weight: 600; color: #2d3748;">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #718096;">วันที่สมัคร</td>
                <td style="padding: 6px 0; text-align: right; font-weight: 600; color: #2d3748;">{{ now()->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <p style="text-align: center;">
        <a href="{{ url('/products') }}" class="btn">🛍️ เริ่มช้อปปิ้งเลย</a>
    </p>

    <hr class="divider">

    <p class="text" style="font-size: 13px; color: #a0aec0;">
        หากคุณไม่ได้สมัครสมาชิก กรุณาเพิกเฉยอีเมลนี้
    </p>
@endsection
