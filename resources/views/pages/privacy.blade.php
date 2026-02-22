@extends('layouts.app', [
    'seoTitle' => 'นโยบายความเป็นส่วนตัว — PGMF Shop',
    'seoDescription' => 'นโยบายความเป็นส่วนตัวและการคุ้มครองข้อมูลส่วนบุคคลของ PGMF Shop',
])

@section('content')
    <div class="min-h-screen bg-white">

        <!-- Hero -->
        <div class="border-b border-gray-100">
            <div class="container mx-auto px-4 py-16 md:py-20 max-w-3xl text-center">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">นโยบายความเป็นส่วนตัว</h1>
                <p class="text-gray-500 mt-3 text-sm">PGMF Shop ให้ความสำคัญกับการคุ้มครองข้อมูลส่วนบุคคลของคุณ</p>
                <p class="text-xs text-gray-400 mt-2">อัปเดตล่าสุด: 1 มกราคม 2569</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12 max-w-3xl">

            <!-- 1 -->
            <section class="mb-10">
                <h2 class="text-lg font-bold text-gray-900 mb-3">1. ข้อมูลที่เราเก็บรวบรวม</h2>
                <div class="text-sm text-gray-600 leading-relaxed space-y-3">
                    <p>เราเก็บรวบรวมข้อมูลส่วนบุคคลเมื่อคุณ:</p>
                    <ul class="list-disc list-inside space-y-1.5 text-gray-500 ml-2">
                        <li>สมัครสมาชิกหรือเข้าสู่ระบบ (ชื่อ, อีเมล, เบอร์โทรศัพท์)</li>
                        <li>สั่งซื้อสินค้า (ที่อยู่จัดส่ง, ข้อมูลการชำระเงิน)</li>
                        <li>ติดต่อฝ่ายบริการลูกค้า</li>
                        <li>เข้าชมเว็บไซต์ (ข้อมูลการใช้งาน, คุกกี้)</li>
                    </ul>
                </div>
            </section>

            <hr class="border-gray-100 mb-10">

            <!-- 2 -->
            <section class="mb-10">
                <h2 class="text-lg font-bold text-gray-900 mb-3">2. วัตถุประสงค์ในการใช้ข้อมูล</h2>
                <div class="text-sm text-gray-600 leading-relaxed space-y-3">
                    <p>เราใช้ข้อมูลส่วนบุคคลของคุณเพื่อ:</p>
                    <ul class="list-disc list-inside space-y-1.5 text-gray-500 ml-2">
                        <li>ดำเนินการและจัดส่งคำสั่งซื้อ</li>
                        <li>ยืนยันตัวตนและรักษาความปลอดภัยของบัญชี</li>
                        <li>ติดต่อสื่อสารเกี่ยวกับคำสั่งซื้อและบริการ</li>
                        <li>ปรับปรุงและพัฒนาบริการของเรา</li>
                        <li>ส่งข้อมูลโปรโมชั่นและข่าวสาร (เฉพาะกรณีที่คุณยินยอม)</li>
                    </ul>
                </div>
            </section>

            <hr class="border-gray-100 mb-10">

            <!-- 3 -->
            <section class="mb-10">
                <h2 class="text-lg font-bold text-gray-900 mb-3">3. การเปิดเผยข้อมูล</h2>
                <div class="text-sm text-gray-600 leading-relaxed space-y-3">
                    <p>เราจะไม่ขาย แลกเปลี่ยน หรือเปิดเผยข้อมูลส่วนบุคคลของคุณแก่บุคคลภายนอก ยกเว้นในกรณีต่อไปนี้:</p>
                    <ul class="list-disc list-inside space-y-1.5 text-gray-500 ml-2">
                        <li><strong class="text-gray-600">บริษัทขนส่ง</strong> — เพื่อจัดส่งสินค้าไปยังที่อยู่ของคุณ</li>
                        <li><strong class="text-gray-600">ผู้ให้บริการชำระเงิน</strong> — เพื่อดำเนินการชำระเงิน</li>
                        <li><strong class="text-gray-600">หน่วยงานราชการ</strong> — เมื่อกฎหมายกำหนด</li>
                    </ul>
                </div>
            </section>

            <hr class="border-gray-100 mb-10">

            <!-- 4 -->
            <section class="mb-10">
                <h2 class="text-lg font-bold text-gray-900 mb-3">4. การรักษาความปลอดภัยของข้อมูล</h2>
                <div class="text-sm text-gray-600 leading-relaxed space-y-3">
                    <p>เราใช้มาตรการรักษาความปลอดภัยที่เหมาะสมเพื่อปกป้องข้อมูลส่วนบุคคลของคุณ ได้แก่:</p>
                    <ul class="list-disc list-inside space-y-1.5 text-gray-500 ml-2">
                        <li>การเข้ารหัสข้อมูลด้วย SSL/TLS</li>
                        <li>การจำกัดสิทธิ์การเข้าถึงข้อมูล</li>
                        <li>การตรวจสอบและอัปเดตระบบรักษาความปลอดภัยอย่างสม่ำเสมอ</li>
                        <li>การเก็บรหัสผ่านในรูปแบบที่เข้ารหัส (hashed)</li>
                    </ul>
                </div>
            </section>

            <hr class="border-gray-100 mb-10">

            <!-- 5 -->
            <section class="mb-10">
                <h2 class="text-lg font-bold text-gray-900 mb-3">5. คุกกี้ (Cookies)</h2>
                <div class="text-sm text-gray-600 leading-relaxed space-y-3">
                    <p>เว็บไซต์ของเราใช้คุกกี้เพื่อ:</p>
                    <ul class="list-disc list-inside space-y-1.5 text-gray-500 ml-2">
                        <li>จดจำการเข้าสู่ระบบและการตั้งค่าของคุณ</li>
                        <li>จัดเก็บสินค้าในตะกร้า</li>
                        <li>วิเคราะห์การใช้งานเว็บไซต์เพื่อปรับปรุงบริการ</li>
                    </ul>
                    <p>คุณสามารถปิดการใช้งานคุกกี้ได้ผ่านการตั้งค่าเบราว์เซอร์ แต่อาจส่งผลต่อการใช้งานบางฟีเจอร์ของเว็บไซต์</p>
                </div>
            </section>

            <hr class="border-gray-100 mb-10">

            <!-- 6 -->
            <section class="mb-10">
                <h2 class="text-lg font-bold text-gray-900 mb-3">6. สิทธิ์ของเจ้าของข้อมูล</h2>
                <div class="text-sm text-gray-600 leading-relaxed space-y-3">
                    <p>ตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA) คุณมีสิทธิ์:</p>
                    <ul class="list-disc list-inside space-y-1.5 text-gray-500 ml-2">
                        <li>เข้าถึงและขอสำเนาข้อมูลส่วนบุคคลของคุณ</li>
                        <li>ขอแก้ไขข้อมูลที่ไม่ถูกต้อง</li>
                        <li>ขอลบข้อมูลส่วนบุคคล</li>
                        <li>คัดค้านการประมวลผลข้อมูล</li>
                        <li>ขอให้ระงับการใช้ข้อมูล</li>
                        <li>ถอนความยินยอมที่เคยให้ไว้</li>
                    </ul>
                </div>
            </section>

            <hr class="border-gray-100 mb-10">

            <!-- 7 -->
            <section class="mb-10">
                <h2 class="text-lg font-bold text-gray-900 mb-3">7. การเปลี่ยนแปลงนโยบาย</h2>
                <div class="text-sm text-gray-600 leading-relaxed">
                    <p>เราอาจปรับปรุงนโยบายความเป็นส่วนตัวนี้เป็นครั้งคราว การเปลี่ยนแปลงใดๆ จะถูกประกาศบนหน้านี้พร้อมระบุวันที่อัปเดต เราแนะนำให้คุณตรวจสอบนโยบายนี้เป็นประจำ</p>
                </div>
            </section>

            <hr class="border-gray-100 mb-10">

            <!-- Contact -->
            <section class="mb-4">
                <h2 class="text-lg font-bold text-gray-900 mb-3">ติดต่อเรา</h2>
                <p class="text-sm text-gray-600 leading-relaxed mb-4">หากคุณมีคำถามเกี่ยวกับนโยบายความเป็นส่วนตัว หรือต้องการใช้สิทธิ์ตามกฎหมาย กรุณาติดต่อ:</p>
                <div class="flex items-center gap-6 text-sm text-gray-600">
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-envelope class="h-4 w-4 text-gray-400" />
                        support@pgmfshop.com
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-phone class="h-4 w-4 text-gray-400" />
                        02-123-4567
                    </span>
                </div>
            </section>

        </div>
    </div>
@endsection
