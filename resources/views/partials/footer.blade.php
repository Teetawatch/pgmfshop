<footer class="w-full bg-white border-t">
    <!-- Payment & Shipping Bar -->
    <div class="border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-2">ช่องทางการชำระเงิน</p>
                    <div class="flex items-center gap-2">
                        <img src="{{ vite_image('Thai_QR_Payment_Logo-01.jpg') }}" alt="PromptPay" class="h-auto max-h-12 object-contain rounded-lg shadow-sm">
                        <span class=    "inline-flex items-center gap-2.5 h-12 px-5 bg-linear-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transform hover:scale-[1.02] transition-all duration-300 border border-blue-500/20">
                            <x-heroicon-o-building-library class="h-5 w-5" />
                            โอนเงินผ่านธนาคาร
                        </span>
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
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/pgmf_th?igsh=NzRrMnZsbmdsNmds" class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition-colors">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z"/>
                        </svg>
                    </a>
                    <a href="https://lin.ee/I2lbHqqa" class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                        <svg class="h-5 w-5" viewBox="0 0 24 24">
                            <path fill="#00C300" d="M22.167 10.733c0-5.233-4.967-9.467-11.083-9.467S0 5.5 0 10.733c0 4.683 3.683 8.6 8.583 9.333l2.5 4.334 2.5-4.334c4.9-.733 8.584-4.65 8.584-9.333z"/>
                            <path fill="white" d="M6.5 8.5c0-.3.2-.5.5-.5h1c.3 0 .5.2.5.5v3c0 .3-.2.5-.5.5h-1c-.3 0-.5-.2-.5-.5v-3zm3 0c0-.3.2-.5.5-.5h1c.3 0 .5.2.5.5v3c0 .3-.2.5-.5.5h-1c-.3 0-.5-.2-.5-.5v-3zm3 0c0-.3.2-.5.5-.5h1c.3 0 .5.2.5.5v3c0 .3-.2.5-.5.5h-1c-.3 0-.5-.2-.5-.5v-3z"/>
                            <text x="11" y="15" font-family="Arial, sans-serif" font-size="6" font-weight="bold" fill="white" text-anchor="middle">LINE</text>
                        </svg>
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
