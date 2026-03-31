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

            @if($sent)
                {{-- Success state --}}
                <div class="flex justify-center mb-5">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                        <x-heroicon-o-envelope-open class="h-8 w-8 text-green-600" />
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">ตรวจสอบอีเมลของคุณ</h1>
                <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                    เราได้ส่งลิงก์รีเซ็ตรหัสผ่านไปที่<br>
                    <span class="font-semibold text-gray-700">{{ $email }}</span><br>
                    แล้ว กรุณาตรวจสอบกล่องจดหมาย (รวมถึงโฟลเดอร์ Spam)
                </p>

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 text-left mb-6">
                    <p class="text-xs text-blue-700 leading-relaxed">
                        ลิงก์จะหมดอายุภายใน <strong>60 นาที</strong> หากไม่ได้รับอีเมล กรุณากด "ส่งลิงก์อีกครั้ง" ด้านล่าง
                    </p>
                </div>

                <button wire:click="$set('sent', false)"
                        class="w-full py-3 border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-xl text-sm font-medium transition-all duration-200 mb-4">
                    ส่งลิงก์อีกครั้ง
                </button>

                <a href="{{ route('login') }}"
                   class="block w-full py-3 bg-[hsl(var(--primary))] hover:bg-[hsl(var(--primary))]/90 text-white rounded-xl text-sm font-semibold shadow-lg shadow-orange-500/20 transition-all duration-200">
                    กลับไปเข้าสู่ระบบ
                </a>

            @else
                {{-- Form state --}}
                <h1 class="text-2xl font-bold text-gray-900 mb-2">ลืมรหัสผ่าน?</h1>
                <p class="text-gray-500 text-sm mb-8 leading-relaxed">
                    กรอกอีเมลที่ใช้สมัครสมาชิก เราจะส่งลิงก์สำหรับตั้งรหัสผ่านใหม่ให้คุณ
                </p>

                <form wire:submit="sendLink" class="space-y-4 text-left">
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">อีเมล</label>
                        <div class="relative">
                            <x-heroicon-o-envelope class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <input type="email" wire:model="email" placeholder="your@email.com" required
                                   class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[hsl(var(--primary))]/20 focus:border-[hsl(var(--primary))] transition-all duration-200" />
                        </div>
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit"
                            class="w-full py-3 bg-[hsl(var(--primary))] hover:bg-[hsl(var(--primary))]/90 text-white rounded-xl text-sm font-semibold shadow-lg shadow-orange-500/20 transition-all duration-200 flex items-center justify-center gap-2 active:scale-[0.98]"
                            wire:loading.attr="disabled">
                        <svg wire:loading wire:target="sendLink" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <span wire:loading wire:target="sendLink">กำลังส่ง...</span>
                        <span wire:loading.remove wire:target="sendLink">ส่งลิงก์รีเซ็ตรหัสผ่าน</span>
                    </button>
                </form>
            @endif

        </div>

        {{-- Back to login --}}
        @if(!$sent)
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-[hsl(var(--primary))] transition-colors group">
                    <x-heroicon-o-arrow-left class="h-4 w-4 transition-transform group-hover:-translate-x-0.5" />
                    กลับไปเข้าสู่ระบบ
                </a>
            </div>
        @endif

    </div>
</div>
