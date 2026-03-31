@extends('emails.layouts.base')

@section('title', 'รีเซ็ตรหัสผ่าน — ' . config('app.name'))
@section('header-subtitle', 'รีเซ็ตรหัสผ่าน')

@section('content')
    <p class="greeting">สวัสดี</p>

    <p class="text">
        เราได้รับคำขอรีเซ็ตรหัสผ่านสำหรับบัญชีที่ผูกกับอีเมล <strong>{{ $userEmail }}</strong>
    </p>

    <div class="highlight-box warning">
        <p style="font-size: 14px; color: #92400e; margin: 0;">
            <strong>ลิงก์นี้จะหมดอายุภายใน 60 นาที</strong><br>
            หากคุณไม่ได้ขอรีเซ็ตรหัสผ่าน สามารถเพิกเฉยอีเมลนี้ได้เลย
        </p>
    </div>

    <p style="text-align: center; margin: 32px 0;">
        <a href="{{ $resetUrl }}" class="btn">ตั้งรหัสผ่านใหม่</a>
    </p>

    <p class="text" style="font-size: 13px; color: #64748b;">
        หากปุ่มด้านบนไม่ทำงาน ให้คัดลอกลิงก์นี้ไปวางในเบราว์เซอร์:
    </p>
    <div class="info-box" style="word-break: break-all; font-size: 13px; color: #3b82f6;">
        {{ $resetUrl }}
    </div>

    <hr class="divider">

    <p class="text" style="font-size: 13px; color: #a0aec0;">
        หากคุณไม่ได้ขอรีเซ็ตรหัสผ่าน กรุณาเพิกเฉยอีเมลนี้ รหัสผ่านเดิมของคุณจะยังคงใช้งานได้ตามปกติ
    </p>
@endsection
