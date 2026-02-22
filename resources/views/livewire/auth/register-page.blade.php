<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-[420px]">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2.5 mb-6">
                <img src="{{ vite_image('pgmf-logo.jpg') }}" alt="PGMF Shop" class="h-11 w-11 rounded-xl object-cover shadow-lg" />
                <span class="text-2xl font-bold text-[hsl(var(--primary))]">PGMF Shop</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">สร้างบัญชีใหม่</h1>
            <p class="text-gray-500 mt-1.5 text-sm">สมัครสมาชิกฟรี เริ่มช้อปปิ้งได้ทันที</p>
        </div>

        <!-- Promo Banner -->
        <div class="bg-gradient-to-r from-[hsl(var(--primary))]/10 to-[hsl(var(--primary))]/5 rounded-xl px-4 py-3 mb-5 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-[hsl(var(--primary))]/15 flex items-center justify-center shrink-0">
                <x-heroicon-o-gift class="h-4 w-4 text-[hsl(var(--primary))]" />
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
                        <x-heroicon-o-envelope class="h-5 w-5 shrink-0" />
                        <span class="flex-1 text-left">สมัครด้วย E-mail</span>
                        <x-heroicon-o-arrow-right class="h-4 w-4 opacity-60 group-hover:opacity-100 transition-opacity" />
                    </button>
                </div>
            @else
                <button wire:click="setMode('choose')" class="text-sm text-gray-500 hover:text-gray-900 transition-colors mb-5 flex items-center gap-1">
                    <x-heroicon-o-arrow-left class="h-3.5 w-3.5" />
                    กลับ
                </button>

                <form wire:submit="register" class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700">ชื่อ-นามสกุล</label>
                        <div class="relative">
                            <x-heroicon-o-user class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <input type="text" wire:model="name" placeholder="สมชาย ใจดี" required class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[hsl(var(--ring))] focus:border-transparent" />
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700">อีเมล</label>
                        <div class="relative">
                            <x-heroicon-o-envelope class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <input type="email" wire:model="email" placeholder="your@email.com" required class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[hsl(var(--ring))] focus:border-transparent" />
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700">รหัสผ่าน</label>
                        <div class="relative">
                            <x-heroicon-o-lock-closed class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <input type="{{ $showPassword ? 'text' : 'password' }}" wire:model="password" placeholder="อย่างน้อย 6 ตัวอักษร" required class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[hsl(var(--ring))] focus:border-transparent" />
                            <button type="button" wire:click="$toggle('showPassword')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                @if($showPassword)
                                    <x-heroicon-o-eye-slash class="h-4 w-4" />
                                @else
                                    <x-heroicon-o-eye class="h-4 w-4" />
                                @endif
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700">ยืนยันรหัสผ่าน</label>
                        <div class="relative">
                            <x-heroicon-o-lock-closed class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
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
                <x-heroicon-o-shield-check class="h-3.5 w-3.5" />
                <span>ปลอดภัย 100%</span>
            </div>
            <div class="w-1 h-1 rounded-full bg-gray-300"></div>
            <div class="flex items-center gap-1">
                <x-heroicon-o-lock-closed class="h-3.5 w-3.5" />
                <span>เข้ารหัส SSL</span>
            </div>
        </div>
    </div>
</div>
