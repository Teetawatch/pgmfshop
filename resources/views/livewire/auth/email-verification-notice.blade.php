<div class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
            {{-- Icon --}}
            <div class="mx-auto w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                <x-heroicon-o-envelope class="w-10 h-10 text-blue-500" />
            </div>

            <h2 class="text-xl font-bold text-gray-900 mb-2">ยืนยันอีเมลของคุณ</h2>
            <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                เราได้ส่งลิงก์ยืนยันไปที่
                <strong class="text-gray-700">{{ auth()->user()->email }}</strong><br>
                กรุณาตรวจสอบอีเมลของคุณแล้วคลิกลิงก์ยืนยัน
            </p>

            <button wire:click="resend" wire:loading.attr="disabled"
                class="w-full py-3 px-4 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-xl transition-colors disabled:opacity-50">
                <span wire:loading.remove wire:target="resend">ส่งอีเมลยืนยันอีกครั้ง</span>
                <span wire:loading wire:target="resend">กำลังส่ง...</span>
            </button>

            <p class="text-xs text-gray-400 mt-4">
                หากไม่พบอีเมล กรุณาตรวจสอบในโฟลเดอร์ Spam
            </p>

            <div class="mt-6 pt-4 border-t border-gray-100">
                <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    ← กลับหน้าหลัก
                </a>
            </div>
        </div>
    </div>
</div>
