<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-[420px]">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2.5 mb-6">
                <img src="{{ Vite::asset('resources/images/pgmf-logo.jpg') }}" alt="PGMF Shop" class="h-11 w-11 rounded-xl object-cover shadow-lg" />
                <span class="text-2xl font-bold text-[hsl(var(--primary))]">PGMF Shop</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">สร้างบัญชีใหม่</h1>
            <p class="text-gray-500 mt-1.5 text-sm">สมัครสมาชิกฟรี เริ่มช้อปปิ้งได้ทันที</p>
        </div>

        <!-- Promo Banner -->
        <div class="bg-gradient-to-r from-[hsl(var(--primary))]/10 to-[hsl(var(--primary))]/5 rounded-xl px-4 py-3 mb-5 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-[hsl(var(--primary))]/15 flex items-center justify-center shrink-0">
                <svg class="h-4 w-4 text-[hsl(var(--primary))]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 12v10H4V12M2 7h20v5H2zM12 22V7M12 7H7.5a2.5 2.5 0 010-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 000-5C13 2 12 7 12 7z"/></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-[hsl(var(--primary))]">รับส่วนลด 10% ทันที!</p>
                <p class="text-xs text-gray-500">สมัครสมาชิกวันนี้ รับโค้ดส่วนลดสำหรับออเดอร์แรก</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl border shadow-sm p-6 sm:p-8">
            @if($mode === 'choose')
                <div class="space-y-3">
                    <div class="relative py-3">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                        <div class="relative flex justify-center"><span class="bg-white px-4 text-xs text-gray-500">สมัครสมาชิกด้วย</span></div>
                    </div>
                    <button wire:click="setMode('email')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-[hsl(var(--primary))] to-[hsl(var(--primary))]/90 text-white hover:opacity-90 transition-all text-sm font-medium shadow-md group">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <span class="flex-1 text-left">สมัครด้วย E-mail</span>
                        <svg class="h-4 w-4 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                </div>
            @else
                <button wire:click="setMode('choose')" class="text-sm text-gray-500 hover:text-gray-900 transition-colors mb-5 flex items-center gap-1">
                    <svg class="h-3.5 w-3.5 rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    กลับ
                </button>

                <form wire:submit="register" class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700">ชื่อ-นามสกุล</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input type="text" wire:model="name" placeholder="สมชาย ใจดี" required class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[hsl(var(--ring))] focus:border-transparent" />
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700">อีเมล</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <input type="email" wire:model="email" placeholder="your@email.com" required class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[hsl(var(--ring))] focus:border-transparent" />
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700">รหัสผ่าน</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                            <input type="{{ $showPassword ? 'text' : 'password' }}" wire:model="password" placeholder="อย่างน้อย 6 ตัวอักษร" required class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[hsl(var(--ring))] focus:border-transparent" />
                            <button type="button" wire:click="$toggle('showPassword')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                @if($showPassword)
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" x2="23" y1="1" y2="23"/></svg>
                                @else
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                @endif
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700">ยืนยันรหัสผ่าน</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                            <input type="password" wire:model="confirmPassword" placeholder="กรอกรหัสผ่านอีกครั้ง" required class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[hsl(var(--ring))] focus:border-transparent" />
                        </div>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-[hsl(var(--primary))] text-white rounded-xl text-sm font-semibold shadow-md hover:opacity-90 transition-colors flex items-center justify-center gap-2" wire:loading.attr="disabled">
                        <span wire:loading wire:target="register"><svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg></span>
                        <span wire:loading wire:target="register">กำลังสมัคร...</span>
                        <span wire:loading.remove wire:target="register">สมัครสมาชิก</span>
                    </button>
                    <p class="text-[11px] text-gray-500 text-center leading-relaxed">
                        การสมัครสมาชิกหมายความว่าคุณยอมรับ <span class="text-gray-900 underline cursor-pointer">เงื่อนไขการใช้งาน</span> และ <span class="text-gray-900 underline cursor-pointer">นโยบายความเป็นส่วนตัว</span>
                    </p>
                </form>
            @endif

            <div class="mt-6 pt-5 border-t text-center text-sm">
                <span class="text-gray-500">มีบัญชีอยู่แล้ว? </span>
                <a href="{{ route('login') }}" class="text-[hsl(var(--primary))] hover:underline font-semibold">เข้าสู่ระบบ</a>
            </div>
        </div>

        <div class="flex items-center justify-center gap-4 mt-6 text-xs text-gray-500">
            <div class="flex items-center gap-1">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                <span>ปลอดภัย 100%</span>
            </div>
            <div class="w-1 h-1 rounded-full bg-gray-300"></div>
            <div class="flex items-center gap-1">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                <span>เข้ารหัส SSL</span>
            </div>
        </div>
    </div>
</div>
