<div class="relative min-h-[80vh] flex items-center justify-center px-4 py-12 overflow-hidden">

    {{-- Blur decorations --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-orange-200/60 blur-[80px]"></div>
        <div class="absolute -bottom-40 -right-40 w-120 h-120 rounded-full bg-blue-200/60 blur-[80px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.12)] border border-gray-100 p-8 text-center">

            {{-- Logo --}}
            <div class="flex justify-center mb-6">
                <a href="{{ route('home') }}"
                   class="w-16 h-16 rounded-2xl bg-linear-to-br from-orange-400 to-blue-500 p-0.5 shadow-lg block">
                    <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden">
                        <img src="{{ vite_image('pgmf-logo.jpg') }}" alt="PGMF Shop"
                             class="w-full h-full object-cover opacity-90" />
                    </div>
                </a>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">สร้างบัญชีใหม่</h1>
            <p class="text-gray-500 text-sm mb-8">สมัครสมาชิกฟรี เริ่มช้อปปิ้งได้ทันที</p>

            @if($mode === 'choose')
                <div class="space-y-3">
                    <button wire:click="setMode('email')"
                            class="w-full flex items-center justify-center gap-3 bg-gray-900 hover:bg-gray-800 text-white py-3 rounded-xl transition-all duration-200 text-sm font-medium shadow-lg shadow-gray-900/20">
                        <x-heroicon-o-envelope class="h-5 w-5 shrink-0" />
                        <span>สมัครสมาชิกด้วย E-mail</span>
                    </button>
                </div>

            @else
                {{-- Back button --}}
                <button wire:click="setMode('choose')"
                        class="text-sm text-gray-500 hover:text-[hsl(var(--primary))] transition-colors mb-6 flex items-center gap-1.5 group">
                    <x-heroicon-o-arrow-left class="h-4 w-4 transition-transform group-hover:-translate-x-0.5" />
                    กลับไปเลือกวิธีสมัคร
                </button>

                <form wire:submit="register" class="space-y-4 text-left">
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">ชื่อ-นามสกุล</label>
                        <div class="relative">
                            <x-heroicon-o-user class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <input type="text" wire:model="name" placeholder="สมชาย ใจดี" required
                                   class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[hsl(var(--primary))]/20 focus:border-[hsl(var(--primary))] transition-all duration-200" />
                        </div>
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">อีเมล</label>
                        <div class="relative">
                            <x-heroicon-o-envelope class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <input type="email" wire:model="email" placeholder="your@email.com" required
                                   class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[hsl(var(--primary))]/20 focus:border-[hsl(var(--primary))] transition-all duration-200" />
                        </div>
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">รหัสผ่าน</label>
                        <div class="relative" x-data="{ show: false }">
                            <x-heroicon-o-lock-closed class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <input :type="show ? 'text' : 'password'" wire:model="password" placeholder="อย่างน้อย 6 ตัวอักษร" required
                                   class="w-full pl-11 pr-11 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[hsl(var(--primary))]/20 focus:border-[hsl(var(--primary))] transition-all duration-200" />
                            <button type="button" @click="show = !show"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                <x-heroicon-o-eye-slash class="h-4 w-4" x-show="show" style="display:none" />
                                <x-heroicon-o-eye class="h-4 w-4" x-show="!show" />
                            </button>
                        </div>
                        @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">ยืนยันรหัสผ่าน</label>
                        <div class="relative">
                            <x-heroicon-o-lock-closed class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <input type="password" wire:model="confirmPassword" placeholder="กรอกรหัสผ่านอีกครั้ง" required
                                   class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[hsl(var(--primary))]/20 focus:border-[hsl(var(--primary))] transition-all duration-200" />
                        </div>
                        @error('confirmPassword') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit"
                            class="w-full py-3 bg-[hsl(var(--primary))] hover:bg-[hsl(var(--primary))]/90 text-white rounded-xl text-sm font-semibold shadow-lg shadow-orange-500/20 transition-all duration-200 flex items-center justify-center gap-2 active:scale-[0.98]"
                            wire:loading.attr="disabled">
                        <svg wire:loading wire:target="register" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <span wire:loading wire:target="register">กำลังสมัคร...</span>
                        <span wire:loading.remove wire:target="register">สมัครสมาชิก</span>
                    </button>

                    <p class="text-[11px] text-gray-500 text-center leading-relaxed">
                        การสมัครสมาชิกหมายความว่าคุณยอมรับ <span class="text-gray-900 underline cursor-pointer">เงื่อนไขการใช้งาน</span> และ <span class="text-gray-900 underline cursor-pointer">นโยบายความเป็นส่วนตัว</span>
                    </p>
                </form>
            @endif

            {{-- Login link --}}
            <div class="mt-8 text-sm text-gray-600">
                มีบัญชีอยู่แล้ว?
                <a href="{{ route('login') }}" class="text-[hsl(var(--primary))] font-semibold hover:underline">เข้าสู่ระบบ</a>
            </div>

        </div>

        {{-- Trust badges --}}
        <div class="flex justify-center items-center gap-6 mt-8 text-xs text-gray-400">
            <div class="flex items-center gap-1.5">
                <x-heroicon-s-shield-check class="h-4 w-4 text-green-500" />
                <span>ปลอดภัย 100%</span>
            </div>
            <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
            <div class="flex items-center gap-1.5">
                <x-heroicon-s-lock-closed class="h-4 w-4 text-blue-500" />
                <span>เข้ารหัส SSL</span>
            </div>
        </div>

    </div>
</div>
