@extends('layouts.app', [
    'seoTitle' => 'วิธีการสั่งซื้อ — PGMF Shop',
    'seoDescription' => 'ขั้นตอนการสั่งซื้อสินค้าจาก PGMF Shop อย่างง่ายๆ ตั้งแต่เลือกสินค้าจนถึงรับสินค้า',
])

@push('seo')
<style>
    .step-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .step-card:hover {
        transform: translateY(-4px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#ffffff]">

    {{-- ===== HERO HEADER — Centered ===== --}}
    <section class="pt-20 pb-12 text-center">
        <div class="max-w-3xl mx-auto px-6">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 tracking-tight">วิธีการสั่งซื้อ</h1>
            <p class="text-lg text-gray-500">ช้อปง่ายๆ เพียงไม่กี่ขั้นตอน มั่นใจ ปลอดภัย ด้วยมาตรฐาน PGMF Shop</p>
        </div>
    </section>

    {{-- ===== TIMELINE STEPS ===== --}}
    <section class="max-w-4xl mx-auto px-6 pb-24">

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
        <div class="space-y-6">
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

</div>
@endsection
