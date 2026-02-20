@extends('layouts.app', [
    'seoTitle' => 'คำถามที่พบบ่อย (FAQ) — PGMF Shop',
    'seoDescription' => 'คำถามที่พบบ่อยเกี่ยวกับการสั่งซื้อ การชำระเงิน การจัดส่ง และนโยบายต่างๆ ของ PGMF Shop',
])

@section('content')
    <div class="min-h-screen bg-white">

        <!-- Hero -->
        <div class="border-b border-gray-100">
            <div class="container mx-auto px-4 py-16 md:py-20 max-w-3xl text-center">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">คำถามที่พบบ่อย</h1>
                <p class="text-gray-500 mt-3 text-sm">รวมคำตอบสำหรับคำถามที่ลูกค้าสอบถามบ่อย</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12 max-w-3xl">
            @php
                $faqs = [
                    [
                        'category' => 'การสั่งซื้อ',
                        'items' => [
                            ['q' => 'สั่งซื้อสินค้าอย่างไร?', 'a' => 'เลือกสินค้าที่ต้องการ กดเพิ่มลงตะกร้า จากนั้นไปที่หน้าตะกร้าสินค้า กรอกข้อมูลที่อยู่จัดส่ง เลือกวิธีชำระเงิน แล้วยืนยันคำสั่งซื้อ สามารถดูรายละเอียดเพิ่มเติมได้ที่หน้า "วิธีการสั่งซื้อ"'],
                            ['q' => 'สามารถยกเลิกคำสั่งซื้อได้หรือไม่?', 'a' => 'สามารถยกเลิกได้ก่อนที่ร้านค้าจะยืนยันการชำระเงิน โดยไปที่หน้า "คำสั่งซื้อของฉัน" แล้วกดปุ่มยกเลิก หากร้านค้ายืนยันการชำระเงินแล้ว กรุณาติดต่อเจ้าหน้าที่'],
                            ['q' => 'ต้องการแก้ไขที่อยู่จัดส่งหลังสั่งซื้อแล้ว ทำอย่างไร?', 'a' => 'กรุณาติดต่อเจ้าหน้าที่ผ่านทาง Line หรือโทรศัพท์โดยเร็วที่สุด ก่อนที่สินค้าจะถูกจัดส่ง'],
                        ],
                    ],
                    [
                        'category' => 'การชำระเงิน',
                        'items' => [
                            ['q' => 'รับชำระเงินช่องทางใดบ้าง?', 'a' => 'ปัจจุบันรับชำระผ่าน QR PromptPay และโอนเงินผ่านธนาคาร หลังชำระเงินแล้วกรุณาแนบหลักฐานการโอนเงิน (สลิป) ในหน้าคำสั่งซื้อ'],
                            ['q' => 'ชำระเงินแล้วแต่ยังไม่ได้รับการยืนยัน?', 'a' => 'กรุณาตรวจสอบว่าได้แนบสลิปการโอนเงินเรียบร้อยแล้ว ทางร้านจะตรวจสอบและยืนยันภายใน 1-2 ชั่วโมงในเวลาทำการ หากเกินกว่านี้กรุณาติดต่อเจ้าหน้าที่'],
                            ['q' => 'สามารถขอใบเสร็จรับเงินได้หรือไม่?', 'a' => 'ได้ครับ ระบบจะออกใบเสร็จอัตโนมัติหลังจากยืนยันการชำระเงินเรียบร้อย สามารถดาวน์โหลดได้จากหน้ารายละเอียดคำสั่งซื้อ'],
                        ],
                    ],
                    [
                        'category' => 'การจัดส่ง',
                        'items' => [
                            ['q' => 'จัดส่งสินค้าภายในกี่วัน?', 'a' => 'หลังจากยืนยันการชำระเงินเรียบร้อย ทางร้านจะจัดส่งสินค้าภายใน 1-2 วันทำการ และสินค้าจะถึงมือลูกค้าภายใน 1-3 วันทำการ ขึ้นอยู่กับพื้นที่จัดส่ง'],
                            ['q' => 'ใช้บริการขนส่งอะไร?', 'a' => 'เราใช้บริการไปรษณีย์ไทยในการจัดส่งสินค้า'],
                            ['q' => 'ติดตามสถานะการจัดส่งได้อย่างไร?', 'a' => 'หลังจากจัดส่งสินค้า ทางร้านจะแจ้งหมายเลขพัสดุผ่านอีเมลและในหน้ารายละเอียดคำสั่งซื้อ สามารถนำหมายเลขไปตรวจสอบสถานะได้ที่เว็บไซต์ของบริษัทขนส่ง'],
                        ],
                    ],
                    [
                        'category' => 'การคืนสินค้า',
                        'items' => [
                            ['q' => 'สามารถคืนสินค้าได้หรือไม่?', 'a' => 'สามารถคืนสินค้าได้ภายใน 7 วันหลังได้รับสินค้า ในกรณีสินค้าชำรุด เสียหาย หรือไม่ตรงกับที่สั่งซื้อ กรุณาถ่ายรูปสินค้าและติดต่อเจ้าหน้าที่'],
                            ['q' => 'ขั้นตอนการคืนสินค้าเป็นอย่างไร?', 'a' => 'ติดต่อเจ้าหน้าที่แจ้งปัญหาพร้อมแนบรูปภาพ → รอการตรวจสอบและอนุมัติ → จัดส่งสินค้าคืน → รับเงินคืนภายใน 3-5 วันทำการ'],
                        ],
                    ],
                    [
                        'category' => 'บัญชีผู้ใช้',
                        'items' => [
                            ['q' => 'สมัครสมาชิกอย่างไร?', 'a' => 'กดปุ่ม "สมัครสมาชิก" ที่มุมขวาบน กรอกข้อมูลให้ครบถ้วน หรือสมัครผ่าน Google / Facebook ได้ทันที'],
                            ['q' => 'ลืมรหัสผ่าน ทำอย่างไร?', 'a' => 'กดปุ่ม "ลืมรหัสผ่าน" ที่หน้าเข้าสู่ระบบ กรอกอีเมลที่ใช้สมัครสมาชิก ระบบจะส่งลิงก์สำหรับตั้งรหัสผ่านใหม่ไปยังอีเมลของคุณ'],
                        ],
                    ],
                ];
            @endphp

            <div class="space-y-10" x-data="{ open: null }">
                @foreach($faqs as $sectionIndex => $section)
                    <section>
                        <h2 class="text-lg font-bold text-gray-900 mb-4">{{ $section['category'] }}</h2>
                        <div class="border border-gray-200 rounded-lg divide-y divide-gray-200">
                            @foreach($section['items'] as $itemIndex => $item)
                                @php $key = $sectionIndex . '-' . $itemIndex; @endphp
                                <div>
                                    <button
                                        @click="open = open === '{{ $key }}' ? null : '{{ $key }}'"
                                        class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors"
                                    >
                                        <span class="text-sm font-medium text-gray-700 pr-4">{{ $item['q'] }}</span>
                                        <x-heroicon-o-chevron-down class="h-4 w-4 text-gray-400 shrink-0 transition-transform duration-200" x-bind:class="{ 'rotate-180': open === '{{ $key }}' }" />
                                    </button>
                                    <div x-show="open === '{{ $key }}'" x-collapse class="px-5 pb-4">
                                        <p class="text-sm text-gray-500 leading-relaxed">{{ $item['a'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>

            <!-- Still have questions -->
            <hr class="border-gray-100 my-12">
            <section class="text-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 mb-2">ยังมีคำถามเพิ่มเติม?</h3>
                <p class="text-sm text-gray-500 mb-4">ติดต่อทีมงานของเราได้ทุกวัน 09:00 - 21:00 น.</p>
                <div class="flex items-center justify-center gap-6 text-sm text-gray-600">
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-phone class="h-4 w-4 text-gray-400" />
                        02-123-4567
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-envelope class="h-4 w-4 text-gray-400" />
                        support@pgmfshop.com
                    </span>
                </div>
            </section>
        </div>
    </div>
@endsection
