@extends('emails.layouts.base')

@section('title', 'PGMF Shop')
@section('header-subtitle', 'ขอบคุณที่สมัครสมาชิก')

@section('content')
    <p class="greeting">สวัสดีคุณ {{ $user->name }}</p>

    <p class="text">
        ยินดีต้อนรับสู่ <strong>PGMF Shop</strong>!
        บัญชีของคุณถูกสร้างเรียบร้อยแล้ว ขอบคุณที่เลือกใช้บริการของเรา
    </p>

    <div class="highlight-box success">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div>
                <p style="font-size: 16px; color: #166534; margin: 0; font-weight: 600;">
                    สมัครสมาชิกสำเร็จ!
                </p>
                <p style="font-size: 14px; color: #166534; margin: 4px 0 0;">
                    คุณสามารถเริ่มเลือกซื้อสินค้าได้ทันที
                </p>
            </div>
        </div>
    </div>

    <div class="info-box">
        <h3 style="font-size: 16px; color: #0f172a; margin: 0 0 16px; font-weight: 600;">ข้อมูลบัญชีของคุณ</h3>
        <table style="width: 100%; font-size: 14px;">
            <tr>
                <td style="padding: 10px 0; color: #64748b;">ชื่อสมาชิก</td>
                <td style="padding: 10px 0; text-align: right; font-weight: 600; color: #0f172a;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #64748b;">อีเมล</td>
                <td style="padding: 10px 0; text-align: right; font-weight: 600; color: #0f172a;">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #64748b;">วันที่สมัคร</td>
                <td style="padding: 10px 0; text-align: right; font-weight: 600; color: #0f172a;">{{ now()->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <p style="text-align: center;">
        <a href="{{ url('/') }}" class="btn">เริ่มช้อปปิ้งเลย</a>
    </p>

    <hr class="divider">

    <p class="text" style="font-size: 13px; color: #a0aec0;">
        หากคุณไม่ได้สมัครสมาชิก กรุณาเพิกเฉยอีเมลนี้
    </p>
@endsection
