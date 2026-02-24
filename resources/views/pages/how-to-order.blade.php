@extends('layouts.app', [
    'seoTitle' => 'วิธีการสั่งซื้อ — PGMF Shop',
    'seoDescription' => 'ขั้นตอนการสั่งซื้อสินค้าจาก PGMF Shop อย่างง่ายๆ ตั้งแต่เลือกสินค้าจนถึงรับสินค้า',
])

@section('content')
<div class="bg-white">

    {{-- ─── Hero ─── --}}
    <section class="pt-20 pb-12 text-center">
        <div class="max-w-3xl mx-auto px-6">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 tracking-tight text-slate-900">วิธีการสั่งซื้อ</h1>
            <p class="text-lg text-slate-500">ช้อปง่ายๆ เพียงไม่กี่ขั้นตอน มั่นใจ ปลอดภัย ด้วยมาตรฐาน PGMF Shop</p>
            <div class="mt-8 flex justify-center">
                <div class="h-1 w-20 bg-orange-500 rounded-full"></div>
            </div>
        </div>
    </section>

    {{-- ─── Timeline Steps ─── --}}
    @php
        $steps = [
            [
                'title' => 'สมัครสมาชิก / เข้าสู่ระบบ',
                'desc'  => 'เริ่มต้นด้วยการสร้างบัญชีใหม่หรือเข้าสู่ระบบที่คุณมีอยู่แล้ว สามารถเชื่อมต่อผ่าน Google หรือ Facebook ได้ทันที เพื่อความรวดเร็วในการจัดการที่อยู่และการสั่งซื้อ',
                'icon'  => 'user-plus',
                'img'   => 'https://images.unsplash.com/photo-1555421689-491a97ff2040?w=300&q=80',
                'link'  => ['label' => 'สมัครสมาชิก', 'url' => 'register'],
            ],
            [
                'title' => 'เลือกสินค้าและเพิ่มลงตะกร้า',
                'desc'  => 'เลือกชมสินค้าจากหมวดหมู่ต่างๆ หรือค้นหาสินค้าที่คุณต้องการ เมื่อเจอสินค้าที่ถูกใจ เพียงกดปุ่ม "เพิ่มลงตะกร้า" และเลือกจำนวนที่ต้องการ',
                'icon'  => 'shopping-cart',
                'img'   => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=300&q=80',
                'link'  => ['label' => 'ดูสินค้าทั้งหมด', 'url' => 'products'],
            ],
            [
                'title' => 'ตรวจสอบสินค้าในตะกร้า',
                'desc'  => 'กดที่ไอคอนตะกร้าเพื่อตรวจสอบรายการสินค้า จำนวน และราคาให้เรียบร้อย หากมีรหัสส่วนลด คุณสามารถระบุในขั้นตอนนี้เพื่อรับสิทธิพิเศษ',
                'icon'  => 'clipboard-document-list',
                'img'   => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=300&q=80',
                'link'  => ['label' => 'ดูตะกร้าสินค้า', 'url' => 'cart'],
            ],
            [
                'title' => 'กรอกข้อมูลจัดส่งและยืนยัน',
                'desc'  => 'เลือกหรือเพิ่มที่อยู่สำหรับจัดส่ง ตรวจสอบความถูกต้องของข้อมูลอีกครั้งก่อนกดยืนยันการสั่งซื้อ เพื่อให้สินค้าถึงมือท่านอย่างรวดเร็ว',
                'icon'  => 'truck',
                'img'   => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=300&q=80',
                'note'  => 'กรุณาตรวจสอบที่อยู่จัดส่งให้ถูกต้องก่อนยืนยัน เนื่องจากไม่สามารถแก้ไขได้หลังยืนยันคำสั่งซื้อ',
            ],
            [
                'title' => 'ชำระเงินและแนบหลักฐาน',
                'desc'  => 'ชำระเงินผ่านช่องทางที่สะดวก เช่น QR PromptPay หรือโอนเงินผ่านธนาคาร จากนั้นแนบสลิปเพื่อแจ้งการชำระเงินในเมนู "คำสั่งซื้อของฉัน"',
                'icon'  => 'credit-card',
                'img'   => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=300&q=80',
            ],
            [
                'title' => 'รอรับสินค้าที่บ้านคุณ',
                'desc'  => 'เจ้าหน้าที่จะตรวจสอบยอดชำระและเตรียมจัดส่งสินค้าทันที คุณสามารถติดตามสถานะและเลขพัสดุได้ผ่านอีเมลหรือหน้าเว็บบัญชีของคุณ',
                'icon'  => 'gift',
                'img'   => 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?w=300&q=80',
                'link'  => ['label' => 'ติดตามคำสั่งซื้อ', 'url' => 'account.orders'],
            ],
        ];
    @endphp

    <section class="max-w-4xl mx-auto px-6 pb-24 relative">
        {{-- Vertical timeline line --}}
        <div class="absolute left-[calc(1.5rem+28px-1px)] top-0 bottom-0 w-0.5 bg-linear-to-b from-transparent via-gray-200 to-transparent hidden md:block"></div>

        @foreach($steps as $i => $step)
            <div class="relative pl-0 md:pl-20 mb-10 last:mb-0">
                {{-- Number badge --}}
                <div class="hidden md:flex absolute left-0 w-14 h-14 bg-orange-500 text-white items-center justify-center rounded-2xl shadow-lg shadow-orange-200 z-10">
                    <span class="text-2xl font-bold">{{ $i + 1 }}</span>
                </div>

                {{-- Step card --}}
                <div class="bg-white border border-slate-100 p-6 md:p-8 rounded-3xl shadow-sm hover:shadow-xl hover:shadow-orange-500/5 hover:-translate-y-1 transition-all duration-300">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex-1">
                            {{-- Mobile step number --}}
                            <div class="flex items-center gap-3 mb-3 md:hidden">
                                <div class="w-9 h-9 bg-orange-500 text-white flex items-center justify-center rounded-xl text-sm font-bold shrink-0">{{ $i + 1 }}</div>
                            </div>
                            <h3 class="text-xl font-bold mb-3 flex items-center gap-3 text-slate-900">
                                @if($step['icon'] === 'user-plus')
                                    <x-heroicon-o-user-plus class="h-6 w-6 text-orange-500 shrink-0" />
                                @elseif($step['icon'] === 'shopping-cart')
                                    <x-heroicon-o-shopping-cart class="h-6 w-6 text-orange-500 shrink-0" />
                                @elseif($step['icon'] === 'clipboard-document-list')
                                    <x-heroicon-o-clipboard-document-list class="h-6 w-6 text-orange-500 shrink-0" />
                                @elseif($step['icon'] === 'truck')
                                    <x-heroicon-o-truck class="h-6 w-6 text-orange-500 shrink-0" />
                                @elseif($step['icon'] === 'credit-card')
                                    <x-heroicon-o-credit-card class="h-6 w-6 text-orange-500 shrink-0" />
                                @elseif($step['icon'] === 'gift')
                                    <x-heroicon-o-gift class="h-6 w-6 text-orange-500 shrink-0" />
                                @endif
                                {{ $step['title'] }}
                            </h3>
                            <p class="text-slate-600 leading-relaxed">{{ $step['desc'] }}</p>
                            @if(!empty($step['note']))
                                <p class="mt-3 text-sm text-orange-600 bg-orange-50 border border-orange-100 rounded-xl px-4 py-2.5 leading-relaxed">
                                    <span class="font-semibold">หมายเหตุ:</span> {{ $step['note'] }}
                                </p>
                            @endif
                            @if(!empty($step['link']))
                                <a href="{{ route($step['link']['url']) }}"
                                   class="inline-flex items-center gap-1.5 mt-4 text-sm font-semibold text-orange-500 hover:text-orange-600 hover:underline transition-colors">
                                    {{ $step['link']['label'] }}
                                    <x-heroicon-o-arrow-right class="h-4 w-4" />
                                </a>
                            @endif
                        </div>
                        @if(!empty($step['img']))
                            <div class="hidden md:block shrink-0">
                                <img src="{{ $step['img'] }}" alt="{{ $step['title'] }}"
                                     class="rounded-xl w-32 h-24 object-cover opacity-80">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    {{-- ─── CTA ─── --}}
    <section class="max-w-7xl mx-auto px-6 pb-24">
        <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white min-h-[380px] flex items-center justify-center">
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?w=1200&q=80"
                     alt="Shop background" class="w-full h-full object-cover opacity-40 blur-sm">
                <div class="absolute inset-0 bg-linear-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
            </div>
            <div class="relative z-10 text-center px-6 max-w-2xl py-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">พร้อมที่จะเริ่มช้อปแล้วหรือยัง?</h2>
                <p class="text-xl text-slate-300 mb-10">พบกับสินค้าคุณภาพและโปรโมชั่นพิเศษมากมายที่เราคัดสรรมาเพื่อคุณโดยเฉพาะ</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('products') }}"
                       class="inline-flex items-center gap-2 px-10 py-4 bg-orange-500 text-white font-bold rounded-full hover:scale-105 transition-transform shadow-lg shadow-orange-500/30">
                        <x-heroicon-o-shopping-bag class="h-5 w-5" />
                        เริ่มช้อปสินค้าเลย
                    </a>
                    <a href="{{ route('products') }}?sort=bestselling"
                       class="inline-flex items-center gap-2 px-10 py-4 bg-white/10 backdrop-blur-md text-white border border-white/20 font-bold rounded-full hover:bg-white/20 transition-colors">
                        ดูสินค้าขายดี
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
