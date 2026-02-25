<div class="relative min-h-[80vh] flex items-center justify-center px-4 py-12 overflow-hidden">

    {{-- Blur decorations --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-orange-200/60 blur-[80px]"></div>
        <div class="absolute -bottom-40 -right-40 w-120 h-120 rounded-full bg-blue-200/60 blur-[80px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.12)] border border-gray-100 overflow-hidden">

            {{-- Gradient top bar --}}
            <div class="h-1 w-full bg-gradient-to-r from-[hsl(var(--primary))] via-orange-400 to-[#1877F2]"></div>

            <div class="px-8 py-10 text-center">

                {{-- Logo --}}
                <div class="flex justify-center mb-6">
                    <a href="{{ route('home') }}"
                       class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-400 to-blue-500 p-0.5 shadow-lg block">
                        <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('images/pgmf-logo.jpg') }}" alt="PGMF Shop"
                                 class="w-full h-full object-cover opacity-90" />
                        </div>
                    </a>
                </div>

                <h1 class="text-2xl font-bold text-gray-900 mb-2">ยินดีต้อนรับกลับ</h1>
                <p class="text-gray-500 text-sm mb-8">เข้าสู่ระบบเพื่อสั่งซื้อสินค้าและติดตามคำสั่งซื้อ</p>

                @if (session('error'))
                    <div class="mb-6 p-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm flex items-center gap-2 text-left">
                        <x-heroicon-o-x-circle class="h-4 w-4 shrink-0" />
                        {{ session('error') }}
                    </div>
                @endif

                @if($mode === 'choose')
                    <div class="space-y-3">
                        {{-- Google --}}
                        <a href="{{ route('social.redirect', 'google') }}"
                           class="w-full flex items-center justify-center gap-3 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 py-3 rounded-xl transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md group">
                            <svg viewBox="0 0 24 24" class="w-5 h-5 shrink-0">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            <span>เข้าสู่ระบบด้วย Google</span>
                        </a>

                        {{-- Facebook --}}
                        <a href="{{ route('social.redirect', 'facebook') }}"
                           class="w-full flex items-center justify-center gap-3 bg-[#1877F2] hover:bg-[#166fe5] text-white py-3 rounded-xl transition-all duration-200 text-sm font-medium shadow-md shadow-blue-500/20">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 shrink-0">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span>เข้าสู่ระบบด้วย Facebook</span>
                        </a>

                        {{-- Divider --}}
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-xs">
                                <span class="px-3 bg-white text-gray-400">หรือ</span>
                            </div>
                        </div>

                        {{-- Email --}}
                        <button wire:click="setMode('email')"
                                class="w-full flex items-center justify-center gap-3 bg-gray-900 hover:bg-gray-800 text-white py-3 rounded-xl transition-all duration-200 text-sm font-medium shadow-lg shadow-gray-900/20">
                            <x-heroicon-o-envelope class="h-5 w-5 shrink-0" />
                            <span>เข้าสู่ระบบด้วย E-mail</span>
                        </button>
                    </div>

                @else
                    {{-- Back button --}}
                    <button wire:click="setMode('choose')"
                            class="text-sm text-gray-500 hover:text-[hsl(var(--primary))] transition-colors mb-6 flex items-center gap-1.5 group">
                        <x-heroicon-o-arrow-left class="h-4 w-4 transition-transform group-hover:-translate-x-0.5" />
                        กลับไปเลือกวิธีเข้าสู่ระบบ
                    </button>

                    <form wire:submit="login" class="space-y-4 text-left">
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
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-semibold text-gray-700">รหัสผ่าน</label>
                                <button type="button" class="text-xs text-[hsl(var(--primary))] hover:underline font-medium transition-colors">ลืมรหัสผ่าน?</button>
                            </div>
                            <div class="relative" x-data="{ show: false }">
                                <x-heroicon-o-lock-closed class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                                <input :type="show ? 'text' : 'password'" wire:model="password" placeholder="••••••••" required
                                       class="w-full pl-11 pr-11 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[hsl(var(--primary))]/20 focus:border-[hsl(var(--primary))] transition-all duration-200" />
                                <button type="button" @click="show = !show"
                                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    <x-heroicon-o-eye-slash class="h-4 w-4" x-show="show" style="display:none" />
                                    <x-heroicon-o-eye class="h-4 w-4" x-show="!show" />
                                </button>
                            </div>
                            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                                class="w-full py-3 bg-[hsl(var(--primary))] hover:bg-[hsl(var(--primary))]/90 text-white rounded-xl text-sm font-semibold shadow-lg shadow-orange-500/20 transition-all duration-200 flex items-center justify-center gap-2 active:scale-[0.98]"
                                wire:loading.attr="disabled">
                            <svg wire:loading wire:target="login" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            <span wire:loading wire:target="login">กำลังเข้าสู่ระบบ...</span>
                            <span wire:loading.remove wire:target="login">เข้าสู่ระบบ</span>
                        </button>
                    </form>
                @endif

                {{-- Register link --}}
                <div class="mt-8 text-sm text-gray-600">
                    ยังไม่มีบัญชี?
                    <a href="{{ route('register') }}" class="text-[hsl(var(--primary))] font-semibold hover:underline">สมัครสมาชิกฟรี</a>
                </div>

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
