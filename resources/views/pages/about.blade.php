@extends('layouts.app', [
    'seoTitle' => 'เกี่ยวกับเรา — PGMF Shop',
    'seoDescription' => 'เรียนรู้เกี่ยวกับ Progressive Movement Foundation Shop ร้านค้าออนไลน์ที่รวบรวมหนังสือ เสื้อผ้า และสินค้าคุณภาพ',
])

@section('content')
    <div class="min-h-screen bg-white">

        <!-- Hero -->
        <div class="border-b border-gray-100">
            <div class="container mx-auto px-4 py-16 md:py-20 max-w-3xl text-center">
                <img src="{{ vite_image('pgmf-logo.jpg') }}" alt="PGMF Shop" class="h-16 w-16 rounded-xl object-cover mx-auto mb-6 ring-1 ring-gray-200">
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
                            <x-heroicon-o-shield-check class="h-5 w-5 text-gray-600" />
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">สินค้าคุณภาพ</h3>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">คัดสรรสินค้าจากแบรนด์ชั้นนำ รับประกันคุณภาพทุกชิ้น</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            <x-heroicon-o-currency-dollar class="h-5 w-5 text-gray-600" />
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">ราคาคุ้มค่า</h3>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">ราคาที่เข้าถึงได้ พร้อมโปรโมชั่นและส่วนลดพิเศษ</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            <x-heroicon-o-bolt class="h-5 w-5 text-gray-600" />
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">จัดส่งรวดเร็ว</h3>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">จัดส่งภายใน 1-3 วันทำการ ผ่านไปรษณีย์ไทย</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            <x-heroicon-o-lifebuoy class="h-5 w-5 text-gray-600" />
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
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            <x-heroicon-o-envelope class="h-4 w-4 text-gray-500" />
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">อีเมล</p>
                            <p class="text-sm text-gray-700 font-medium">Office@progressivemevement.in.th.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            <x-heroicon-o-map-pin class="h-4 w-4 text-gray-500" />
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">ที่อยู่</p>
                            <p class="text-sm text-gray-700 font-medium">167 อาคารอนาคตใหม่ ชั้น 3 ยูนิตที่1 แขวงหัวหมาก เขตบางกะปิ กทม. 10240</p>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-6">เปิดให้บริการทุกวันจันทร์ - ศุกร์ เวลา 09.00 - 19.00 น.</p>
            </section>

        </div>
    </div>
@endsection
