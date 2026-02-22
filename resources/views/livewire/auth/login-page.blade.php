<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-[440px]">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2.5 mb-6 group">
                <img src="{{ vite_image('pgmf-logo.jpg') }}" alt="PGMF Shop" class="h-12 w-12 rounded-2xl object-cover shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-shadow duration-300" />
                <span class="text-2xl font-extrabold bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent"></span>
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900">ยินดีต้อนรับกลับ</h1>
            <p class="text-gray-500 mt-2 text-sm">เข้าสู่ระบบเพื่อสั่งซื้อสินค้าและติดตามคำสั่งซื้อ</p>
        </div>

        <!-- Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl border border-white/60 shadow-xl shadow-gray-200/50 p-6 sm:p-8">
            @if (session('error'))
                <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm flex items-center gap-2">
                    <x-heroicon-o-x-circle class="h-4 w-4 shrink-0" />
                    {{ session('error') }}
                </div>
            @endif

            @if($mode === 'choose')
                <div class="space-y-3">
                    <!-- Social Login Buttons -->
                    <a href="{{ route('social.redirect', 'google') }}" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl bg-white border-2 border-gray-100 hover:border-red-200 hover:bg-red-50/50 transition-all duration-200 text-sm font-semibold text-gray-700 hover:text-red-600 shadow-sm hover:shadow-md hover:shadow-red-100/50 group">
                        <div class="w-6 h-6 shrink-0">
                            <svg viewBox="0 0 24 24" class="w-6 h-6">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                        </div>
                        <span class="flex-1 text-left">เข้าสู่ระบบด้วย Google</span>
                        <x-heroicon-o-arrow-right class="h-4 w-4 opacity-0 group-hover:opacity-100 transition-all duration-200 translate-x-0 group-hover:translate-x-1" />
                    </a>

                    <a href="{{ route('social.redirect', 'facebook') }}" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl bg-gradient-to-r from-[#1877F2] to-[#0C63D4] text-white hover:from-[#166AE0] hover:to-[#0B59BF] transition-all duration-200 text-sm font-semibold shadow-md shadow-blue-500/25 hover:shadow-lg hover:shadow-blue-500/30 group">
                        <div class="w-6 h-6 shrink-0">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </div>
                        <span class="flex-1 text-left">เข้าสู่ระบบด้วย Facebook</span>
                        <x-heroicon-o-arrow-right class="h-4 w-4 opacity-0 group-hover:opacity-100 transition-all duration-200 translate-x-0 group-hover:translate-x-1" />
                    </a>

                    <!-- Divider -->
                    <div class="relative py-4">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200/80"></div></div>
                        <div class="relative flex justify-center">
                            <span class="bg-white/80 px-4 text-xs font-medium text-gray-400 uppercase tracking-wider">หรือ</span>
                        </div>
                    </div>

                    <!-- Email -->
                    <button wire:click="setMode('email')" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl bg-gray-800 text-white hover:bg-gray-700 transition-all duration-200 text-sm font-semibold shadow-sm group">
                        <x-heroicon-o-envelope class="h-5 w-5 shrink-0" />
                        <span class="flex-1 text-left">เข้าสู่ระบบด้วย E-mail</span>
                        <x-heroicon-o-arrow-right class="h-4 w-4 opacity-0 group-hover:opacity-100 transition-all duration-200 translate-x-0 group-hover:translate-x-1" />
                    </button>
                </div>
            @else
                <!-- Back to choose -->
                <button wire:click="setMode('choose')" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors mb-5 flex items-center gap-1.5 group">
                    <x-heroicon-o-arrow-left class="h-4 w-4 transition-transform group-hover:-translate-x-0.5" />
                    กลับไปเลือกวิธีเข้าสู่ระบบ
                </button>

                <form wire:submit="login" class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">อีเมล</label>
                        <div class="relative">
                            <x-heroicon-o-envelope class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <input type="email" wire:model="email" placeholder="your@email.com" required class="w-full pl-11 pr-4 py-3 border-2 border-gray-100 rounded-2xl text-sm bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all duration-200" />
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-semibold text-gray-700">รหัสผ่าน</label>
                            <button type="button" class="text-xs text-indigo-500 hover:text-indigo-700 hover:underline font-medium transition-colors">ลืมรหัสผ่าน?</button>
                        </div>
                        <div class="relative">
                            <x-heroicon-o-lock-closed class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <input type="{{ $showPassword ? 'text' : 'password' }}" wire:model="password" placeholder="••••••••" required class="w-full pl-11 pr-11 py-3 border-2 border-gray-100 rounded-2xl text-sm bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all duration-200" />
                            <button type="button" wire:click="$toggle('showPassword')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-500 transition-colors">
                                @if($showPassword)
                                    <x-heroicon-o-eye-slash class="h-4 w-4" />
                                @else
                                    <x-heroicon-o-eye class="h-4 w-4" />
                                @endif
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="w-full py-3 bg-gray-800 hover:bg-gray-700 text-white rounded-2xl text-sm font-bold shadow-sm transition-all duration-200 flex items-center justify-center gap-2" wire:loading.attr="disabled">
                        <span wire:loading wire:target="login"><svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg></span>
                        <span wire:loading wire:target="login">กำลังเข้าสู่ระบบ...</span>
                        <span wire:loading.remove wire:target="login">เข้าสู่ระบบ</span>
                    </button>
                </form>
            @endif

            <!-- Register link -->
            <div class="mt-6 pt-5 border-t border-gray-100 text-center text-sm">
                <span class="text-gray-500">ยังไม่มีบัญชี? </span>
                <a href="{{ route('register') }}" class=" font-bold hover:underline transition-all">สมัครสมาชิกฟรี</a>
            </div>
        </div>

        <!-- Trust badges -->
        <div class="flex items-center justify-center gap-5 mt-6 text-xs text-gray-400">
            <div class="flex items-center gap-1.5">
                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                    <x-heroicon-o-shield-check class="h-3 w-3 text-white" />
                </div>
                <span class="font-medium text-gray-500">ปลอดภัย 100%</span>
            </div>
            <div class="w-1 h-1 rounded-full bg-gray-300"></div>
            <div class="flex items-center gap-1.5">
                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center">
                    <x-heroicon-o-lock-closed class="h-3 w-3 text-white" />
                </div>
                <span class="font-medium text-gray-500">เข้ารหัส SSL</span>
            </div>
        </div>
    </div>
</div>
