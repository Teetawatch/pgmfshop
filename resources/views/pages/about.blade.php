@extends('layouts.app', [
    'seoTitle' => 'เกี่ยวกับเรา — PGMF Shop',
    'seoDescription' => 'เรียนรู้เกี่ยวกับ Progressive Movement Foundation Shop ร้านค้าออนไลน์ที่รวบรวมหนังสือ เสื้อผ้า และสินค้าคุณภาพ',
])

@section('content')
    <div class="min-h-screen bg-white">

        <!-- Hero -->
        <div class="border-b border-gray-100">
            <div class="container mx-auto px-4 py-16 md:py-20 max-w-3xl text-center">
                <img src="{{ asset('images/pgmf-logo.jpg') }}" alt="PGMF Shop" class="h-16 w-16 rounded-xl object-cover mx-auto mb-6 ring-1 ring-gray-200">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">Progressive Movement Foundation Shop</h1>
                <p class="text-gray-500 mt-3 text-sm leading-relaxed max-w-lg mx-auto">ร้านค้าออนไลน์ของมูลนิธิคณะก้าวหน้า จำหน่ายสินค้าคุณภาพ สนับสนุนกิจกรรมเพื่อสังคม</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12 max-w-3xl">

            <!-- About -->
            <section class="mb-12">
                <h2 class="text-lg font-bold text-gray-900 mb-4">เกี่ยวกับเรา</h2>
                <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
                    <p><strong class="text-gray-800">PGMF Shop</strong> เป็นร้านค้าออนไลน์ที่มุ่งมั่นนำเสนอสินค้าคุณภาพในราคาที่เข้าถึงได้ เราคัดสรรสินค้าจากแบรนด์ชั้นนำทั้งในและต่างประเทศ เพื่อตอบสนองความต้องการของลูกค้าทุกท่าน</p>
                    <p>เราเชื่อว่าการช้อปปิ้งออนไลน์ควรเป็นเรื่องง่าย สะดวก และปลอดภัย ด้วยระบบที่ทันสมัยและทีมงานที่พร้อมให้บริการ เราจึงมุ่งมั่นพัฒนาประสบการณ์การซื้อสินค้าออนไลน์ให้ดียิ่งขึ้นอย่างต่อเนื่อง</p>
                </div>
            </section>

            <!-- Divider -->
            <hr class="border-gray-100 mb-12">

            <!-- Why Us -->
            <section class="mb-12">
                <h2 class="text-lg font-bold text-gray-900 mb-6">ทำไมต้องเลือกเรา</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">สินค้าคุณภาพ</h3>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">คัดสรรสินค้าจากแบรนด์ชั้นนำ รับประกันคุณภาพทุกชิ้น</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">ราคาคุ้มค่า</h3>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">ราคาที่เข้าถึงได้ พร้อมโปรโมชั่นและส่วนลดพิเศษ</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">จัดส่งรวดเร็ว</h3>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">จัดส่งภายใน 1-3 วันทำการ ผ่านไปรษณีย์ไทย</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">บริการหลังการขาย</h3>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">ทีมงานพร้อมให้บริการและช่วยเหลือทุกวัน 09:00 - 21:00</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Divider -->
            <hr class="border-gray-100 mb-12">

            <!-- Contact -->
            <section class="mb-4">
                <h2 class="text-lg font-bold text-gray-900 mb-6">ติดต่อเรา</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">โทรศัพท์</p>
                            <p class="text-sm text-gray-700 font-medium">02-123-4567</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">อีเมล</p>
                            <p class="text-sm text-gray-700 font-medium">support@pgmfshop.com</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">ที่อยู่</p>
                            <p class="text-sm text-gray-700 font-medium">กรุงเทพมหานคร, ประเทศไทย</p>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-6">เปิดให้บริการทุกวัน 09:00 - 21:00 น.</p>
            </section>

        </div>
    </div>
@endsection
