@extends('layouts.app', [
    'seoTitle' => 'วิธีการสั่งซื้อ — PGMF Shop',
    'seoDescription' => 'ขั้นตอนการสั่งซื้อสินค้าจาก PGMF Shop อย่างง่ายๆ ตั้งแต่เลือกสินค้าจนถึงรับสินค้า',
])

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- ===== HERO HEADER ===== --}}
    <div class="relative overflow-hidden bg-[#FF6B00]">
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-16">
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-orange-200 mb-5">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">หน้าแรก</a>
                <span class="material-icons-outlined text-xs">chevron_right</span>
                <span class="text-white font-medium">วิธีการสั่งซื้อ</span>
            </div>
            {{-- Title --}}
            <div class="flex items-center gap-4">
                <div class="p-3 bg-white/10 rounded-xl">
                    <span class="material-icons-outlined text-3xl text-white">help_outline</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">วิธีการสั่งซื้อ</h1>
                    <p class="text-orange-100 text-sm mt-0.5">ช้อปง่ายๆ เพียงไม่กี่ขั้นตอน มั่นใจ ปลอดภัย</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT (overlaps hero) ===== --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 mb-16 relative z-20">

        @php
            $steps = [
                [
                    'title' => 'สมัครสมาชิก / เข้าสู่ระบบ',
                    'desc'  => 'เริ่มต้นด้วยการสร้างบัญชีใหม่หรือเข้าสู่ระบบที่คุณมีอยู่แล้ว สามารถเชื่อมต่อผ่าน Google หรือ Facebook ได้ทันที เพื่อความรวดเร็วในการจัดการที่อยู่และการสั่งซื้อ',
                    'icon'  => 'person_add',
                    'link'  => ['label' => 'สมัครสมาชิก', 'url' => 'register'],
                ],
                [
                    'title' => 'เลือกสินค้าและเพิ่มลงตะกร้า',
                    'desc'  => 'เลือกชมสินค้าจากหมวดหมู่ต่างๆ หรือค้นหาสินค้าที่คุณต้องการ เมื่อเจอสินค้าที่ถูกใจ เพียงกดปุ่ม "เพิ่มลงตะกร้า" และเลือกจำนวนที่ต้องการ',
                    'icon'  => 'add_shopping_cart',
                    'link'  => ['label' => 'ดูสินค้าทั้งหมด', 'url' => 'products'],
                ],
                [
                    'title' => 'ตรวจสอบสินค้าในตะกร้า',
                    'desc'  => 'กดที่ไอคอนตะกร้าเพื่อตรวจสอบรายการสินค้า จำนวน และราคาให้เรียบร้อย หากมีรหัสส่วนลด คุณสามารถระบุในขั้นตอนนี้เพื่อรับสิทธิพิเศษ',
                    'icon'  => 'shopping_cart_checkout',
                    'link'  => ['label' => 'ดูตะกร้าสินค้า', 'url' => 'cart'],
                ],
                [
                    'title' => 'กรอกข้อมูลจัดส่งและยืนยัน',
                    'desc'  => 'เลือกหรือเพิ่มที่อยู่สำหรับจัดส่ง ตรวจสอบความถูกต้องของข้อมูลอีกครั้งก่อนกดยืนยันการสั่งซื้อ เพื่อให้สินค้าถึงมือท่านอย่างรวดเร็ว',
                    'icon'  => 'local_shipping',
                    'note'  => 'กรุณาตรวจสอบที่อยู่จัดส่งให้ถูกต้องก่อนยืนยัน เนื่องจากไม่สามารถแก้ไขได้หลังยืนยันคำสั่งซื้อ',
                ],
                [
                    'title' => 'ชำระเงินและแนบหลักฐาน',
                    'desc'  => 'ชำระเงินผ่านช่องทางที่สะดวก เช่น QR PromptPay หรือโอนเงินผ่านธนาคาร จากนั้นแนบสลิปเพื่อแจ้งการชำระเงินในเมนู "คำสั่งซื้อของฉัน"',
                    'icon'  => 'payment',
                ],
                [
                    'title' => 'รอรับสินค้าที่บ้านคุณ',
                    'desc'  => 'เจ้าหน้าที่จะตรวจสอบยอดชำระและเตรียมจัดส่งสินค้าทันที คุณสามารถติดตามสถานะและเลขพัสดุได้ผ่านอีเมลหรือหน้าเว็บบัญชีของคุณ',
                    'icon'  => 'inventory_2',
                    'link'  => ['label' => 'ติดตามคำสั่งซื้อ', 'url' => 'account.orders'],
                ],
            ];
        @endphp

        {{-- Steps --}}
        <div class="space-y-4">
            @foreach($steps as $i => $step)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="p-5 flex items-start gap-5">
                        {{-- Number + Icon --}}
                        <div class="shrink-0 flex flex-col items-center gap-2">
                            <div class="w-10 h-10 rounded-lg bg-orange-100 text-[#ff6b00] flex items-center justify-center">
                                <span class="material-icons-outlined">{{ $step['icon'] }}</span>
                            </div>
                            <span class="text-xs font-bold text-gray-400">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        {{-- Content --}}
                        <div class="flex-1 min-w-0 py-0.5">
                            <h3 class="text-base font-semibold text-gray-900 mb-1">{{ $step['title'] }}</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                            @if(!empty($step['note']))
                                <div class="mt-3 flex items-start gap-2 text-sm text-orange-700 bg-orange-50 border border-orange-100 rounded-lg px-3 py-2.5 leading-relaxed">
                                    <span class="material-icons-outlined text-base shrink-0 mt-0.5">info_outline</span>
                                    <span><span class="font-semibold">หมายเหตุ:</span> {{ $step['note'] }}</span>
                                </div>
                            @endif
                            @if(!empty($step['link']))
                                <a href="{{ route($step['link']['url']) }}"
                                   class="inline-flex items-center gap-1 mt-3 text-sm font-medium text-[#ff6b00] hover:text-orange-600 hover:underline transition-colors">
                                    {{ $step['link']['label'] }}
                                    <span class="material-icons-outlined text-base">arrow_forward</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ─── CTA ─── --}}
        <div class="mt-8 bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-orange-100 rounded-xl text-[#ff6b00] shrink-0">
                        <span class="material-icons-outlined text-3xl">storefront</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">พร้อมที่จะเริ่มช้อปแล้วหรือยัง?</h2>
                        <p class="text-sm text-gray-500 mt-0.5">พบกับสินค้าคุณภาพและโปรโมชั่นพิเศษมากมายที่เราคัดสรรมาเพื่อคุณ</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('products') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#ff6b00] text-white rounded-full text-sm font-medium hover:bg-orange-600 transition-colors shadow-sm">
                        <span class="material-icons-outlined text-base">shopping_bag</span>
                        เริ่มช้อปสินค้าเลย
                    </a>
                    <a href="{{ route('products') }}?sort=bestselling"
                       class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-200 bg-white text-gray-600 rounded-full text-sm hover:bg-gray-50 transition-colors">
                        ดูสินค้าขายดี
                    </a>
                </div>
            </div>
        </div>

    </main>

</div>
@endsection
