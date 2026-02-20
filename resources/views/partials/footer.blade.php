<footer class="bg-white border-t">
    <!-- Payment & Shipping Bar -->
    <div class="border-b">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-2">ช่องทางการชำระเงิน</p>
                    <div class="flex items-center gap-2">
                        <img src="{{ vite_image('Thai_QR_Payment_Logo-01.jpg') }}" alt="PromptPay" class="h-auto max-h-12 object-contain rounded-lg shadow-sm">
                        <span class="inline-flex items-center gap-2.5 h-12 px-5 bg-linear-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transform hover:scale-[1.02] transition-all duration-300 border border-blue-500/20">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3"/>
                                <circle cx="12" cy="10" r="2" fill="currentColor"/>
                            </svg>
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
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="space-y-3">
                <a href="/" class="inline-flex items-center gap-2 mb-1">
                    <img src="{{ vite_image('pgmf-logo.jpg') }}" alt="PGMF Shop" class="h-8 w-8 rounded-lg object-cover" />
                    <span class="text-sm font-bold text-gray-800">PGMF Shop</span>
                </a>
                <div class="space-y-1.5 text-xs text-gray-500">
                    <p>PGMF Shop ร้านค้าออนไลน์ครบวงจร</p>
                    <p>สินค้าคุณภาพ ราคาดี จัดส่งรวดเร็ว</p>
                    <p>เปิดให้บริการทุกวัน 09:00 - 21:00</p>
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
                    <a href="{{ route('products', ['category' => 'สินค้าที่ระลึก']) }}" class="block text-gray-500 hover:text-gray-800">สินค้าที่ระลึก</a>
                    <a href="{{ route('products', ['category' => 'หนังสือ']) }}" class="block text-gray-500 hover:text-gray-800">หนังสือ</a>
                    <a href="{{ route('products', ['category' => 'รวมเรื่องสั้น']) }}" class="block text-gray-500 hover:text-gray-800">รวมเรื่องสั้น</a>
                    <a href="{{ route('products', ['category' => 'เรื่องเล่าจากต่างแดน']) }}" class="block text-gray-500 hover:text-gray-800">เรื่องเล่าจากต่างแดน</a>
                    <a href="{{ route('products', ['category' => 'นิยาย']) }}" class="block text-gray-500 hover:text-gray-800">นิยาย</a>
                    <a href="{{ route('products', ['category' => 'ความรู้อ่านสนุก']) }}" class="block text-gray-500 hover:text-gray-800">ความรู้อ่านสนุก</a>
                    <a href="{{ route('account.orders') }}" class="block text-gray-500 hover:text-gray-800">ติดตามคำสั่งซื้อ</a>
                </div>
            </div>
            <div class="space-y-3">
                <h3 class="text-sm font-bold text-gray-800">ติดต่อเรา</h3>
                <div class="space-y-1.5 text-xs text-gray-500">
                    <div class="flex items-center gap-1.5">
                        <svg class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                        <span>02-123-4567</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <span>support@pgmfshop.com</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>167 อาคารอนาคตใหม่ ชั้น 3 ยูนิตที่1 แขวงหัวหมาก เขตบางกะปิ กทม. 10240</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <a href="#" class="w-10 h-10 rounded-lg bg-[#1877F2] flex items-center justify-center text-white hover:bg-[#166FE5] transition-colors">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-lg bg-linear-to-br from-[#F58529] via-[#DD2A7B] to-[#8134AF] flex items-center justify-center text-white hover:shadow-lg transition-all">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-lg bg-black flex items-center justify-center text-white hover:bg-gray-800 transition-colors">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-lg bg-[#FF0000] flex items-center justify-center text-white hover:bg-[#CC0000] transition-colors">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-lg bg-[#00C300] flex items-center justify-center text-white hover:bg-[#00A000] transition-colors">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t">
        <div class="container mx-auto px-4 py-3 text-center text-[11px] text-gray-400">
            &copy; 2026 PGMF Shop. All rights reserved.
        </div>
    </div>
</footer>
