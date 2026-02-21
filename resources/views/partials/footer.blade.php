<footer class="w-full bg-white border-t">
    <!-- Payment & Shipping Bar -->
    <div class="border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-2">ช่องทางการชำระเงิน</p>
                    <div class="flex items-center gap-2">
                        <img src="{{ vite_image('Thai_QR_Payment_Logo-01.jpg') }}" alt="PromptPay" class="h-auto max-h-12 object-contain rounded-lg shadow-sm">
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-2">บริการจัดส่งสินค้า</p>
                    <div class="flex items-center gap-2">
                        <img src="{{ vite_image('ThailandPost_Logo.svg') }}" alt="ไปรษณีย์ไทย" class="h-12 object-contain">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="space-y-3">
                <a href="/" class="inline-flex items-center gap-2 mb-1">
                    <img src="{{ vite_image('pgmf-logo.jpg') }}" alt="PGMF Shop" class="h-8 w-8 rounded-lg object-cover" />
                    <span class="text-sm font-bold text-gray-800">เกี่ยวกับเรา</span>
                </a>
                <div class="space-y-1.5 text-xs text-gray-500">
                    <p>ร่วมสนับสนุนมูลนิธิคณะก้าวหน้าไปด้วยกันที่ PGMFshop</p>
                    <p>เปิดให้บริการทุกวันจันทร์ - ศุกร์ เวลา 09.00 - 19.00 น.</p>
                </div>
            </div>
            <div class="space-y-3">
                <h3 class="text-sm font-bold text-gray-800">ข้อมูล</h3>
                <div class="space-y-1.5 text-xs">
                    <a href="{{ route('about') }}" class="block text-gray-500 hover:text-gray-800">รายละเอียดร้าน</a>
                    <a href="{{ route('faq') }}" class="block text-gray-500 hover:text-gray-800">คำถามที่พบบ่อย</a>
                    <a href="{{ route('how-to-order') }}" class="block text-gray-500 hover:text-gray-800">วิธีการสั่งซื้อ</a>
                    <a href="{{ route('privacy') }}" class="block text-gray-500 hover:text-gray-800">นโยบายความเป็นส่วนตัว</a>
                </div>
            </div>
            <div class="space-y-3">
                <h3 class="text-sm font-bold text-gray-800">ซื้อสินค้า</h3>
                <div class="space-y-1.5 text-xs">
                    <a href="{{ route('products') }}" class="block text-gray-500 hover:text-gray-800">สินค้าทั้งหมด</a>
                    @php
                        $footerCategories = \App\Models\Category::withCount('products')->orderBy('name')->take(7)->get();
                    @endphp
                    @foreach($footerCategories as $cat)
                        <a href="{{ route('products', ['category' => $cat->slug]) }}" class="block text-gray-500 hover:text-gray-800">{{ $cat->name }}</a>
                    @endforeach
                    <a href="{{ route('account.orders') }}" class="block text-gray-500 hover:text-gray-800">ติดตามคำสั่งซื้อ</a>
                </div>
            </div>
            <div class="space-y-3">
                <h3 class="text-sm font-bold text-gray-800">ติดต่อเรา</h3>
                <div class="space-y-1.5 text-xs text-gray-500">
                    <div class="flex items-center gap-1.5">
                        <x-heroicon-o-envelope class="h-3 w-3 shrink-0" />
                        <span>support@pgmfshop.com</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <x-heroicon-o-map-pin class="h-3 w-3 shrink-0" />
                        <span>167 อาคารอนาคตใหม่ ชั้น 3 ยูนิตที่1 แขวงหัวหมาก เขตบางกะปิ กทม. 10240</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <a href="https://www.facebook.com/commonschoolth" class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition-colors">
                        <img src="{{ asset('images/social/facebook-logo-svgrepo-com.svg') }}" alt="Facebook" class="w-5 h-5">
                    </a>
                    <a href="https://www.instagram.com/pgmf_th?igsh=NzRrMnZsbmdsNmds" class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition-colors">
                        <img src="{{ asset('images/social/instagram-round-svgrepo-com.svg') }}" alt="Instagram" class="w-5 h-5">
                    </a>
                    <a href="https://www.youtube.com/@pgmfshop" class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition-colors">
                        <img src="{{ asset('images/social/youtube-round-svgrepo-com.svg') }}" alt="YouTube" class="w-5 h-5">
                    </a>
                    <a href="https://lin.ee/I2lbHqqa" class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition-colors">
                        <img src="{{ asset('images/social/line-logo-svgrepo-com.svg') }}" alt="LINE" class="w-5 h-5">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="w-full border-t bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 text-center text-[11px] text-gray-400">
            &copy; 2026 Progressive Movement Foundation Shop. All rights reserved.
        </div>
    </div>
</footer>
