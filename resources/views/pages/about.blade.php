@extends('layouts.app', [
    'seoTitle' => 'เกี่ยวกับเรา — PGMF Shop',
    'seoDescription' => 'เรียนรู้เกี่ยวกับ Progressive Movement Foundation Shop ร้านค้าออนไลน์ที่รวบรวมหนังสือ เสื้อผ้า และสินค้าคุณภาพ',
])

@section('content')
<div class="bg-white">

    {{-- ─── Hero ─── --}}
    <section class="py-20 lg:py-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">

            {{-- Logo + Title --}}
            <div class="flex flex-col items-center text-center mb-20">
                <div class="mb-8 p-1 bg-linear-to-tr from-orange-500 to-blue-800 rounded-2xl">
                    <img src="{{ vite_image('pgmf-logo.jpg') }}" alt="PGMF Shop"
                         class="h-24 w-24 bg-white rounded-[calc(1rem-2px)] object-cover">
                </div>
                <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6 max-w-4xl leading-tight">
                    Progressive Movement <br class="hidden md:block"> Foundation Shop
                </h1>
                <p class="text-lg text-gray-500 max-w-2xl leading-relaxed">
                    ร้านค้าออนไลน์ของมูลนิธิคณะก้าวหน้า จำหน่ายสินค้าคุณภาพเพื่อสนับสนุนกิจกรรมเพื่อสังคม
                    ขับเคลื่อนการเปลี่ยนแปลงที่ยั่งยืนผ่านการบริโภคที่สร้างสรรค์
                </p>
            </div>

            {{-- About: image + text --}}
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="aspect-4/5 rounded-xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=800&q=80"
                             alt="PGMF Shop" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-10 -right-10 hidden lg:block w-64 h-64 bg-slate-50 -z-10 rounded-full blur-3xl opacity-60"></div>
                </div>

                <div>
                    <span class="inline-block px-4 py-1.5 rounded-full bg-orange-50 text-orange-600 text-xs font-bold uppercase tracking-wider mb-6">เกี่ยวกับเรา</span>
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">เราเชื่อมั่นในคุณภาพและ <br> เจตจำนงที่ชัดเจน</h2>
                    <div class="space-y-6 text-gray-600 leading-relaxed text-lg">
                        <p>
                            PGMF Shop ภายใต้มูลนิธิคณะก้าวหน้า มีภารกิจในการผลิตและแปลหนังสือเนื้อหาก้าวหน้า เพื่อส่งเสริมประชาธิปไตย สิทธิมนุษยชน ความเท่าเทียมทางเพศ ความหลากหลายทางวัฒนธรรม และความยุติธรรม เพื่อขับเคลื่อนสังคมให้ก้าวหน้า
                        </p>
                        <p>
                            พร้อมกันนี้ ยังพัฒนาพื้นที่จำหน่ายสินค้าที่ระลึกเชิงสร้างสรรค์ เพื่อเป็นอีกช่องทางในการสื่อสารแนวคิด และเปิดโอกาสให้ผู้สนับสนุนมีส่วนร่วมกับการเปลี่ยนแปลง
                        </p>
                        <div class="pt-4 flex items-center gap-6">
                            <div class="flex flex-col">
                                <span class="text-3xl font-bold text-orange-500">10k+</span>
                                <span class="text-sm text-gray-400">ลูกค้าพึงพอใจ</span>
                            </div>
                            <div class="w-px h-10 bg-gray-200"></div>
                            <div class="flex flex-col">
                                <span class="text-3xl font-bold text-orange-500">500+</span>
                                <span class="text-sm text-gray-400">รายการสินค้า</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ─── Contact ─── --}}
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-12 gap-12">

                {{-- Contact Info --}}
                <div class="lg:col-span-5">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">ติดต่อเรา</h2>
                    <p class="text-gray-500 mb-10 leading-relaxed">
                        ต้องการสอบถามข้อมูลเพิ่มเติมเกี่ยวกับสินค้าหรือบริการ? เรายินดีให้ความช่วยเหลือเสมอ
                    </p>

                    <div class="space-y-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 shrink-0 bg-slate-100 rounded-lg flex items-center justify-center text-orange-500">
                                <x-heroicon-o-envelope class="h-5 w-5" />
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">อีเมล</h4>
                                <p class="text-gray-900 font-medium">Office@progressivemovement.in.th</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 shrink-0 bg-slate-100 rounded-lg flex items-center justify-center text-orange-500">
                                <x-heroicon-o-map-pin class="h-5 w-5" />
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">ที่อยู่</h4>
                                <p class="text-gray-900 font-medium">
                                    167 อาคารอนาคตใหม่ ชั้น 3 ยูนิตที่ 1 แขวงหัวหมาก<br>
                                    เขตบางกะปิ กทม. 10240
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 shrink-0 bg-slate-100 rounded-lg flex items-center justify-center text-orange-500">
                                <x-heroicon-o-clock class="h-5 w-5" />
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">เวลาทำการ</h4>
                                <p class="text-gray-900 font-medium">เปิดให้บริการทุกวันจันทร์ - ศุกร์ เวลา 09.00 - 19.00 น.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 flex gap-4">
                        <a href="https://pgmfshop.in.th/" target="_blank" rel="noopener"
                           class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-gray-500 hover:bg-orange-500 hover:text-white transition-all">
                            <x-heroicon-o-globe-alt class="h-5 w-5" />
                        </a>
                        <a href="https://www.facebook.com/progressivemovementfoundation" target="_blank" rel="noopener"
                           class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-gray-500 hover:bg-orange-500 hover:text-white transition-all">
                            <x-heroicon-o-chat-bubble-left-ellipsis class="h-5 w-5" />
                        </a>
                    </div>
                </div>

                {{-- Map --}}
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-xl overflow-hidden shadow-xl h-full min-h-[400px] relative border border-slate-100">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.6!2d100.6397!3d13.7563!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d61a2e5c9f4c7%3A0x1234567890abcdef!2z4Liq4Liy4LiB4LiZ4LmA4LiX4Li14LmI4LiX4LiZ4Li1!5e0!3m2!1sth!2sth!4v1700000000000!5m2!1sth!2sth"
                            width="100%" height="100%"
                            class="absolute inset-0 w-full h-full grayscale contrast-125 opacity-80"
                            style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        <div class="relative z-10 flex items-end justify-start h-full p-6 pointer-events-none">
                            <div class="bg-white p-5 rounded-2xl shadow-2xl flex items-center gap-4 max-w-sm pointer-events-auto">
                                <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white shrink-0">
                                    <x-heroicon-o-map-pin class="h-5 w-5" />
                                </div>
                                <div class="text-sm">
                                    <p class="font-bold text-gray-900">Future Forward Building</p>
                                    <p class="text-gray-500">Bang Kapi, Bangkok 10240</p>
                                    <a href="https://maps.google.com/?q=167+อาคารอนาคตใหม่+บางกะปิ+กรุงเทพ" target="_blank" rel="noopener"
                                       class="text-orange-500 font-semibold mt-2 inline-block hover:underline">
                                        ดูแผนที่ขนาดใหญ่ →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection
