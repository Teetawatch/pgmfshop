@extends('emails.layouts.base')

@section('header-subtitle', 'แจ้งเตือนข้อความติดต่อใหม่')

@section('content')
    <p class="greeting">📩 ข้อความติดต่อใหม่</p>
    <p class="text">มีข้อความติดต่อใหม่จากลูกค้าผ่านหน้าติดต่อร้านค้า</p>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">จากลูกค้า</span>
            <span class="info-value">{{ $contactMessage->user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">อีเมล</span>
            <span class="info-value">{{ $contactMessage->user->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">หัวข้อ</span>
            <span class="info-value">{{ $contactMessage->subject_label }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">วันที่ส่ง</span>
            <span class="info-value">{{ $contactMessage->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <div class="highlight-box info">
        <p style="font-size: 14px; color: #1e40af; font-weight: 600; margin-bottom: 8px;">ข้อความ:</p>
        <p style="font-size: 14px; color: #334155; line-height: 1.7; white-space: pre-line;">{{ $contactMessage->message }}</p>
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/admin/contact-messages/' . $contactMessage->id) }}" class="btn">ดูและตอบกลับข้อความ</a>
    </div>

    <hr class="divider">
    <p class="text" style="font-size: 13px; color: #94a3b8;">
        อีเมลนี้ส่งอัตโนมัติจากระบบ เมื่อมีลูกค้าส่งข้อความติดต่อผ่านเว็บไซต์ PGMF Shop
    </p>
@endsection
