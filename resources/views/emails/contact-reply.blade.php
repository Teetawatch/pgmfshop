@extends('emails.layouts.base')

@section('header-subtitle', 'ตอบกลับข้อความติดต่อ')

@section('content')
    <p class="greeting">สวัสดีคุณ {{ $contactMessage->user->name }} 👋</p>
    <p class="text">ทีมงาน PGMF Shop ได้ตอบกลับข้อความของคุณแล้ว</p>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">หัวข้อ</span>
            <span class="info-value">{{ $contactMessage->subject_label }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">วันที่ส่ง</span>
            <span class="info-value">{{ $contactMessage->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">วันที่ตอบกลับ</span>
            <span class="info-value">{{ $contactMessage->replied_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <div class="highlight-box">
        <p style="font-size: 14px; color: #64748b; font-weight: 600; margin-bottom: 8px;">ข้อความของคุณ:</p>
        <p style="font-size: 14px; color: #475569; line-height: 1.7; white-space: pre-line;">{{ $contactMessage->message }}</p>
    </div>

    <div class="highlight-box success">
        <p style="font-size: 14px; color: #166534; font-weight: 600; margin-bottom: 8px;">คำตอบจากทีมงาน:</p>
        <p style="font-size: 14px; color: #334155; line-height: 1.7; white-space: pre-line;">{{ $contactMessage->admin_reply }}</p>
    </div>

    <p class="text">หากคุณมีคำถามเพิ่มเติม สามารถส่งข้อความถึงเราได้อีกครั้งผ่านหน้าติดต่อร้านค้า</p>

    <div style="text-align: center;">
        <a href="{{ url('/contact') }}" class="btn">ติดต่อร้านค้า</a>
    </div>

    <hr class="divider">
    <p class="text" style="font-size: 13px; color: #94a3b8;">
        อีเมลนี้ส่งจาก PGMF Shop เพื่อตอบกลับข้อความติดต่อของคุณ
    </p>
@endsection
