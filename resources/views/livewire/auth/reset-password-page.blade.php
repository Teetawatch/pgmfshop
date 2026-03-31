<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">

        <div class="bg-white rounded-3xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.12)] border border-gray-100 p-8 text-center">

            {{-- Logo --}}
            <div class="flex justify-center mb-6">
                <a href="{{ route('home') }}"
                   class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-400 to-blue-500 p-0.5 shadow-lg block">
                    <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden">
                        <img src="{{ vite_image('pgmf-logo.jpg') }}" alt="PGMF Shop"
                             class="w-full h-full object-cover opacity-90" />
                    </div>
                </a>
            </div>

            @if($done)
                {{-- Success state --}}
                <div class="flex justify-center mb-5">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                        <x-heroicon-o-check-circle class="h-8 w-8 text-green-600" />
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">ตั้งรหัสผ่านใหม่สำเร็จ!</h1>
                <p class="text-gray-500 text-sm mb-8">
                    รหัสผ่านของคุณถูกเปลี่ยนแล้ว คุณสามารถเข้าสู่ระบบด้วยรหัสผ่านใหม่ได้ทันที
                </p>

                <a href="{{ route('login') }}"
                   class="block w-full py-3 bg-[hsl(var(--primary))] hover:bg-[hsl(var(--primary))]/90 text-white rounded-xl text-sm font-semibold shadow-lg shadow-orange-500/20 transition-all duration-200">
                    เข้าสู่ระบบ
                </a>

            @else
                {{-- Form state --}}
                <h1 class="text-2xl font-bold text-gray-900 mb-2">ตั้งรหัสผ่านใหม่</h1>
                <p class="text-gray-500 text-sm mb-8">กรอกรหัสผ่านใหม่สำหรับบัญชี <span class="font-semibold text-gray-700">{{ $email }}</span></p>

                <form wire:submit="resetPassword" class="space-y-4 text-left">

                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">รหัสผ่านใหม่</label>
                        <div class="relative" x-data="{ show: false }">
                            <x-heroicon-o-lock-closed class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <input :type="show ? 'text' : 'password'" wire:model="password" placeholder="อย่างน้อย 8 ตัวอักษร" required
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
                        <label class="text-sm font-semibold text-gray-700">ยืนยันรหัสผ่านใหม่</label>
                        <div class="relative" x-data="{ show: false }">
                            <x-heroicon-o-lock-closed class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" placeholder="กรอกรหัสผ่านอีกครั้ง" required
                                   class="w-full pl-11 pr-11 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[hsl(var(--primary))]/20 focus:border-[hsl(var(--primary))] transition-all duration-200" />
                            <button type="button" @click="show = !show"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                <x-heroicon-o-eye-slash class="h-4 w-4" x-show="show" style="display:none" />
                                <x-heroicon-o-eye class="h-4 w-4" x-show="!show" />
                            </button>
                        </div>
                        @error('password_confirmation') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit"
                            class="w-full py-3 bg-[hsl(var(--primary))] hover:bg-[hsl(var(--primary))]/90 text-white rounded-xl text-sm font-semibold shadow-lg shadow-orange-500/20 transition-all duration-200 flex items-center justify-center gap-2 active:scale-[0.98]"
                            wire:loading.attr="disabled">
                        <svg wire:loading wire:target="resetPassword" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <span wire:loading wire:target="resetPassword">กำลังบันทึก...</span>
                        <span wire:loading.remove wire:target="resetPassword">บันทึกรหัสผ่านใหม่</span>
                    </button>
                </form>
            @endif

        </div>

    </div>
</div>
