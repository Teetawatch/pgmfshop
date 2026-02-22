@extends('layouts.app', [
    'seoTitle' => 'วิธีการสั่งซื้อ — PGMF Shop',
    'seoDescription' => 'ขั้นตอนการสั่งซื้อสินค้าจาก PGMF Shop อย่างง่ายๆ ตั้งแต่เลือกสินค้าจนถึงรับสินค้า',
])

@section('content')
    <div class="min-h-screen bg-white">

        <!-- Hero -->
        <div class="border-b border-gray-100">
            <div class="container mx-auto px-4 py-16 md:py-20 max-w-3xl text-center">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">วิธีการสั่งซื้อ</h1>
                <p class="text-gray-500 mt-3 text-sm">ขั้นตอนง่ายๆ ในการสั่งซื้อสินค้ากับ PGMF Shop</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12 max-w-3xl">

            <div class="space-y-8">
                @php
                    $steps = [
                        ['title' => 'สมัครสมาชิก / เข้าสู่ระบบ', 'desc' => 'สร้างบัญชีใหม่หรือเข้าสู่ระบบด้วยบัญชีที่มีอยู่ สามารถสมัครด้วยอีเมล, Google หรือ Facebook ได้ทันที'],
                        ['title' => 'เลือกสินค้าและเพิ่มลงตะกร้า', 'desc' => 'เลือกดูสินค้าจากหมวดหมู่ต่างๆ หรือค้นหาสินค้าที่ต้องการ เมื่อพบสินค้าที่ชอบ กดปุ่ม "เพิ่มลงตะกร้า" เลือกจำนวนที่ต้องการ'],
                        ['title' => 'ตรวจสอบตะกร้าสินค้า', 'desc' => 'กดไอคอนตะกร้าที่มุมขวาบน ตรวจสอบรายการสินค้า จำนวน และราคารวม สามารถแก้ไขจำนวนหรือลบสินค้าออกได้ หากมีคูปองส่วนลด สามารถกรอกรหัสคูปองได้ในขั้นตอนถัดไป'],
                        ['title' => 'กรอกข้อมูลจัดส่งและยืนยันคำสั่งซื้อ', 'desc' => 'กดปุ่ม "ดำเนินการสั่งซื้อ" เพื่อไปยังหน้าชำระเงิน กรอกข้อมูลที่อยู่จัดส่ง ตรวจสอบรายการสินค้าอีกครั้ง แล้วกดยืนยันคำสั่งซื้อ', 'note' => 'กรุณาตรวจสอบที่อยู่จัดส่งให้ถูกต้องก่อนยืนยัน เนื่องจากไม่สามารถแก้ไขได้หลังยืนยันคำสั่งซื้อ'],
                        ['title' => 'ชำระเงินและแนบหลักฐาน', 'desc' => 'ชำระเงินผ่าน QR PromptPay หรือโอนเงินผ่านธนาคาร จากนั้นแนบหลักฐานการโอนเงิน (สลิป) ในหน้ารายละเอียดคำสั่งซื้อ'],
                        ['title' => 'รอตรวจสอบและจัดส่ง', 'desc' => 'ทางร้านจะตรวจสอบหลักฐานการชำระเงินและยืนยันคำสั่งซื้อ จากนั้นจัดส่งสินค้าพร้อมแจ้งหมายเลขพัสดุทางอีเมล สามารถติดตามสถานะได้ที่หน้า "คำสั่งซื้อของฉัน"'],
                    ];
                @endphp

                @foreach($steps as $i => $step)
                    <div class="flex gap-5">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center text-sm font-bold shrink-0">{{ $i + 1 }}</div>
                            @if($i < count($steps) - 1)
                                <div class="w-px flex-1 bg-gray-200 mt-2"></div>
                            @endif
                        </div>
                        <div class="pb-8">
                            <h3 class="text-sm font-semibold text-gray-900 mb-1">{{ $step['title'] }}</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                            @if(!empty($step['note']))
                                <p class="text-xs text-gray-400 mt-2 italic">* {{ $step['note'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- CTA -->
            <hr class="border-gray-100 my-12">
            <section class="text-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 mb-2">พร้อมช้อปแล้วหรือยัง?</h3>
                <p class="text-sm text-gray-500 mb-5">เริ่มเลือกซื้อสินค้าคุณภาพได้เลย</p>
                <a href="{{ route('products') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    <x-heroicon-o-shopping-cart class="h-4 w-4" />
                    เลือกซื้อสินค้า
                </a>
            </section>

        </div>
    </div>
@endsection
