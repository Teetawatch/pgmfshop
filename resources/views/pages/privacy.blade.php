@extends('layouts.app', [
    'seoTitle' => 'นโยบายความเป็นส่วนตัว — PGMF Shop',
    'seoDescription' => 'นโยบายความเป็นส่วนตัวและการคุ้มครองข้อมูลส่วนบุคคลของ PGMF Shop',
])

@section('content')
<div class=" min-h-screen">

    {{-- Hero --}}
    <div class="bg-white py-16 sm:py-20 text-center">
        <div class="max-w-3xl mx-auto px-4">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-[hsl(var(--primary))]/10 mb-5">
                <span class="material-icons-outlined text-[hsl(var(--primary))] text-3xl">verified_user</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight mb-3">นโยบายความเป็นส่วนตัว</h1>
            <p class="text-gray-500 max-w-xl mx-auto">PGMF Shop ให้ความสำคัญกับการคุ้มครองข้อมูลส่วนบุคคลของคุณ เพื่อความมั่นใจและความปลอดภัยสูงสุดในการใช้บริการ</p>
            <p class="text-sm text-gray-400 mt-3">อัปเดตล่าสุด: 1 มกราคม 2569</p>
        </div>
    </div>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-10 space-y-6">

        {{-- 1. ข้อมูลที่เราเก็บรวบรวม --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <span class="material-icons-outlined">inventory_2</span>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">1. ข้อมูลที่เราเก็บรวบรวม</h2>
                    <p class="text-gray-600 mb-4 leading-relaxed">เราเก็บรวบรวมข้อมูลส่วนบุคคลเมื่อคุณ:</p>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>สมัครสมาชิกหรือเข้าสู่ระบบ (ชื่อ, อีเมล, เบอร์โทรศัพท์)</li>
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>สั่งซื้อสินค้า (ที่อยู่จัดส่ง, ข้อมูลการชำระเงิน)</li>
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>ติดต่อฝ่ายบริการลูกค้า</li>
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>เข้าชมเว็บไซต์ (ข้อมูลการใช้งาน, คุกกี้)</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 2. วัตถุประสงค์ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                    <span class="material-icons-outlined">ads_click</span>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">2. วัตถุประสงค์ในการใช้ข้อมูล</h2>
                    <p class="text-gray-600 mb-4 leading-relaxed">เราใช้ข้อมูลส่วนบุคคลของคุณเพื่อ:</p>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>ดำเนินการและจัดส่งคำสั่งซื้อ</li>
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>ยืนยันตัวตนและรักษาความปลอดภัยของบัญชี</li>
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>ติดต่อสื่อสารเกี่ยวกับคำสั่งซื้อและบริการ</li>
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>ปรับปรุงและพัฒนาบริการของเรา</li>
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>ส่งข้อมูลโปรโมชั่นและข่าวสาร (เฉพาะกรณีที่คุณยินยอม)</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 3. การเปิดเผยข้อมูล --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                    <span class="material-icons-outlined">share</span>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">3. การเปิดเผยข้อมูล</h2>
                    <p class="text-gray-600 mb-4 leading-relaxed">เราจะไม่ขาย แลกเปลี่ยน หรือเปิดเผยข้อมูลส่วนบุคคลของคุณแก่บุคคลภายนอก ยกเว้นในกรณีต่อไปนี้:</p>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span><span><strong>บริษัทขนส่ง</strong> — เพื่อจัดส่งสินค้าไปยังที่อยู่ของคุณ</span></li>
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span><span><strong>ผู้ให้บริการชำระเงิน</strong> — เพื่อดำเนินการชำระเงิน</span></li>
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span><span><strong>หน่วยงานราชการ</strong> — เมื่อกฎหมายกำหนด</span></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 4. การรักษาความปลอดภัย --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                    <span class="material-icons-outlined">lock</span>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">4. การรักษาความปลอดภัยของข้อมูล</h2>
                    <p class="text-gray-600 mb-4 leading-relaxed">เราใช้มาตรการรักษาความปลอดภัยที่เหมาะสมเพื่อปกป้องข้อมูลส่วนบุคคลของคุณ ได้แก่:</p>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>การเข้ารหัสข้อมูลด้วย SSL/TLS</li>
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>การจำกัดสิทธิ์การเข้าถึงข้อมูล</li>
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>การตรวจสอบและอัปเดตระบบรักษาความปลอดภัยอย่างสม่ำเสมอ</li>
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>การเก็บรหัสผ่านในรูปแบบที่เข้ารหัส (Hashed)</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 5. คุกกี้ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center text-yellow-600">
                    <span class="material-icons-outlined">cookie</span>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">5. คุกกี้ (Cookies)</h2>
                    <p class="text-gray-600 mb-4 leading-relaxed">เว็บไซต์ของเราใช้คุกกี้เพื่อ:</p>
                    <ul class="space-y-2 mb-4">
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>จดจำการเข้าสู่ระบบและการตั้งค่าของคุณ</li>
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>จัดเก็บสินค้าในตะกร้า</li>
                        <li class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>วิเคราะห์การใช้งานเว็บไซต์เพื่อปรับปรุงบริการ</li>
                    </ul>
                    <p class="text-sm text-gray-500 italic bg-gray-50 px-4 py-3 rounded-xl border border-gray-100">คุณสามารถปิดการใช้งานคุกกี้ได้ผ่านการตั้งค่าเบราว์เซอร์ แต่อาจส่งผลต่อการใช้งานบางฟีเจอร์ของเว็บไซต์</p>
                </div>
            </div>
        </div>

        {{-- 6. สิทธิ์ของเจ้าของข้อมูล --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <span class="material-icons-outlined">gavel</span>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">6. สิทธิ์ของเจ้าของข้อมูล</h2>
                    <p class="text-gray-600 mb-4 leading-relaxed">ตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA) คุณมีสิทธิ์:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>เข้าถึงและขอสำเนาข้อมูลส่วนบุคคล</div>
                        <div class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>คัดค้านการประมวลผลข้อมูล</div>
                        <div class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>ขอแก้ไขข้อมูลที่ไม่ถูกต้อง</div>
                        <div class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>ขอให้ระงับการใช้ข้อมูล</div>
                        <div class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>ขอลบข้อมูลส่วนบุคคล</div>
                        <div class="flex items-center gap-2 text-gray-600"><span class="material-icons-outlined text-[hsl(var(--primary))] text-xs">arrow_forward_ios</span>ถอนความยินยอมที่เคยให้ไว้</div>
                    </div>
                </div>
            </div>
        </div>


    </main>
</div>
@endsection
