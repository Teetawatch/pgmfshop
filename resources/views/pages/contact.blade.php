@extends('layouts.app', [
    'title' => 'ติดต่อร้านค้า',
    'description' => 'ติดต่อ PGMF Shop - สอบถามข้อมูลสินค้า สั่งซื้อ และอื่นๆ',
])

@section('content')
<div class="bg-white">

    {{-- Hero Section --}}
    <section class="relative w-full h-64 md:h-80 flex items-center justify-center overflow-hidden bg-linear-to-br from-orange-50 via-white to-orange-50">
        <div class="relative z-10 flex flex-col items-center text-center px-4 max-w-3xl mx-auto space-y-3">
            <span class="inline-block py-1 px-4 rounded-full bg-orange-100 text-orange-600 text-xs font-bold uppercase tracking-wider">ติดต่อเรา</span>
            <h1 class="text-4xl md:text-6xl font-black tracking-tight text-gray-900">ติดต่อร้านค้า</h1>
            <p class="text-base md:text-lg text-gray-500 max-w-xl">
                มีคำถามเกี่ยวกับสินค้าหรือต้องการความช่วยเหลือ? ทีมงานเราพร้อมให้บริการคุณ
            </p>
        </div>
    </section>

    {{-- Main Card --}}
    <div class="w-full max-w-6xl mx-auto px-4 md:px-8 pb-16 -mt-8 relative z-20">
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 overflow-hidden border border-gray-100">
            <div class="flex flex-col lg:flex-row">

                {{-- Left Column: Contact Form --}}
                <div class="w-full lg:w-3/5 p-8 md:p-10 lg:p-14">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">ส่งข้อความถึงร้านค้า</h2>
                        <p class="text-gray-500">กรอกข้อมูลด้านล่าง ทีมงานจะตอบกลับภายใน 24 ชั่วโมง</p>
                    </div>

                    @if(auth()->check())
                        <form class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold uppercase tracking-wide text-gray-400">ชื่อ</label>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}"
                                           class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:border-orange-500 text-gray-900 transition-all"
                                           readonly>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold uppercase tracking-wide text-gray-400">อีเมล</label>
                                    <input type="email" name="email" value="{{ auth()->user()->email }}"
                                           class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:border-orange-500 text-gray-900 transition-all"
                                           readonly>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-wide text-gray-400">หัวข้อ</label>
                                <select name="subject" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:border-orange-500 text-gray-900 transition-all">
                                    <option value="general">สอบถามทั่วไป</option>
                                    <option value="order">สถานะคำสั่งซื้อ</option>
                                    <option value="product">สอบถามสินค้า</option>
                                    <option value="payment">การชำระเงิน</option>
                                    <option value="other">อื่นๆ</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-wide text-gray-400">ข้อความ</label>
                                <textarea name="message" rows="5"
                                          class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:border-orange-500 text-gray-900 transition-all placeholder:text-gray-400 resize-none"
                                          placeholder="เราจะช่วยอะไรคุณได้บ้าง?" required></textarea>
                            </div>
                            <button type="submit"
                                    class="group inline-flex items-center justify-center gap-2 h-12 px-8 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-200 active:scale-95">
                                <span>ส่งข้อความ</span>
                                <x-heroicon-o-paper-airplane class="h-4 w-4 transition-transform group-hover:translate-x-1" />
                            </button>
                        </form>
                    @else
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                                <x-heroicon-o-lock-closed class="h-8 w-8 text-orange-500" />
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">เข้าสู่ระบบเพื่อส่งข้อความ</h3>
                            <p class="text-gray-500 mb-6 max-w-xs">กรุณาเข้าสู่ระบบก่อนส่งข้อความติดต่อร้านค้า</p>
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center gap-2 h-12 px-8 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-200 active:scale-95">
                                <x-heroicon-o-arrow-right-on-rectangle class="h-5 w-5" />
                                เข้าสู่ระบบ
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Right Column: Contact Info --}}
                <div class="w-full lg:w-2/5 bg-gray-50 p-8 md:p-10 lg:p-14 border-t lg:border-t-0 lg:border-l border-gray-100 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-8">ข้อมูลติดต่อ</h3>
                        <div class="space-y-7">

                            {{-- Address --}}
                            <div class="flex items-start gap-4 group">
                                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm text-orange-500 border border-gray-100 group-hover:scale-110 transition-transform shrink-0">
                                    <x-heroicon-o-map-pin class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">ที่อยู่</p>
                                    <p class="text-gray-800 font-medium leading-relaxed text-sm">
                                        167 อาคารอนาคตใหม่ ชั้น 3 ยูนิตที่ 1<br>
                                        แขวงหัวหมาก เขตบางกะปิ<br>
                                        กรุงเทพมหานคร 10240
                                    </p>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="flex items-start gap-4 group">
                                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm text-orange-500 border border-gray-100 group-hover:scale-110 transition-transform shrink-0">
                                    <x-heroicon-o-envelope class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">อีเมล</p>
                                    <a href="mailto:Office@progressivemevement.in.th"
                                       class="text-gray-800 font-medium hover:text-orange-500 transition-colors text-sm">
                                        Office@progressivemevement.in.th
                                    </a>
                                </div>
                            </div>

                            {{-- Business Hours --}}
                            <div class="flex items-start gap-4 group">
                                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm text-orange-500 border border-gray-100 group-hover:scale-110 transition-transform shrink-0">
                                    <x-heroicon-o-clock class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">เวลาทำการ</p>
                                    <p class="text-gray-800 font-medium text-sm">จันทร์ – ศุกร์: 09:00 – 19:00 น.</p>
                                    <p class="text-gray-500 text-sm">เสาร์ – อาทิตย์: หยุดทำการ</p>
                                </div>
                            </div>

                            {{-- Line Official --}}
                            <div class="flex items-start gap-4 group">
                                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm border border-gray-100 group-hover:scale-110 transition-transform shrink-0">
                                    <svg class="w-5 h-5 fill-[#06C755]" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.349 0 .63.285.63.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.281.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Line Official</p>
                                    <a href="https://line.me/ti/p/@pgmfshop" target="_blank"
                                       class="text-gray-800 font-medium hover:text-[#06C755] transition-colors text-sm">
                                        @pgmfshop
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Social Media --}}
                    <div class="mt-10 pt-8 border-t border-gray-200">
                        <p class="text-xs text-gray-500 mb-4 font-medium uppercase tracking-wide">ติดตามเราได้ที่</p>
                        <div class="flex gap-3">
                            <a href="https://www.facebook.com/ProgressiveMovementFoundation" target="_blank"
                               class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-500 hover:bg-orange-500 hover:text-white transition-all shadow-sm border border-gray-100">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="https://www.instagram.com/progressivemovementfoundation" target="_blank"
                               class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-500 hover:bg-orange-500 hover:text-white transition-all shadow-sm border border-gray-100">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Map Section --}}
    <div class="w-full h-96 relative grayscale hover:grayscale-0 transition-all duration-700">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.5455764804266!2d100.6233!3d13.7366!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d61b3f6e7a5c5%3A0x1d7f9a9f8e7a6b2a!2z4Lir4LiZ4LiU4Lit4Lii4LiU4LmD4Lir4Lih4LiU4Liq4Li04LiZ4Lia4Lix4LiZIOC4o-C4tOC4mSAz!5e0!3m2!1sth!2sth!4v1620000000000!5m2!1sth!2sth"
            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
            title="ที่ตั้ง Progressive Movement Foundation">
        </iframe>
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 md:translate-x-0 md:left-10 md:bottom-8 bg-white p-4 rounded-2xl shadow-xl flex items-center gap-4 max-w-xs">
            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-500 shrink-0">
                <x-heroicon-o-map-pin class="h-5 w-5" />
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase">เส้นทาง</p>
                <a href="https://maps.google.com/?q=167+อาคารอนาคตใหม่+แขวงหัวหมาก+เขตบางกะปิ+กรุงเทพมหานคร"
                   target="_blank"
                   class="text-sm font-bold text-gray-800 hover:text-orange-500 transition-colors">
                    เปิดใน Google Maps
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
