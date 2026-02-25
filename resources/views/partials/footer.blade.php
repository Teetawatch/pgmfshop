<footer class="w-full bg-white border-t border-slate-200 pt-16 pb-8 ">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <!-- About -->
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/pgmf-logo.jpg') }}" alt="PGMF Shop" class="w-8 h-8 rounded ">
                    <span class="text-lg font-bold text-slate-900">เกี่ยวกับเรา</span>
                </div>
                <p class="text-slate-500 text-sm leading-relaxed">
                    ร่วมสนับสนุนมูลนิธิคณะก้าวหน้าไปด้วยกันที่ PGMFshop
                    เปิดให้บริการทุกวันจันทร์ - ศุกร์ เวลา 09.00 - 19.00 น.
                </p>
            </div>

            <!-- Information -->
            <div>
                <h4 class="text-slate-900 font-bold mb-6">ข้อมูล</h4>
                <ul class="space-y-3 text-sm text-slate-500">
                    <li><a href="{{ route('about') }}" class="hover:text-[hsl(var(--primary))] transition-colors">รายละเอียดร้าน</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-[hsl(var(--primary))] transition-colors">คำถามที่พบบ่อย</a></li>
                    <li><a href="{{ route('how-to-order') }}" class="hover:text-[hsl(var(--primary))] transition-colors">วิธีการสั่งซื้อ</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-[hsl(var(--primary))] transition-colors">นโยบายความเป็นส่วนตัว</a></li>
                </ul>
            </div>

            <!-- Shopping -->
            <div>
                <h4 class="text-slate-900 font-bold mb-6">ซื้อสินค้า</h4>
                <ul class="space-y-3 text-sm text-slate-500">
                    <li><a href="{{ route('products') }}" class="hover:text-[hsl(var(--primary))] transition-colors">สินค้าทั้งหมด</a></li>
                    @php
                        $footerCategories = \App\Models\Category::withCount('products')->orderBy('name')->take(5)->get();
                    @endphp
                    @foreach($footerCategories as $cat)
                        <li><a href="{{ route('products', ['category' => $cat->slug]) }}" class="hover:text-[hsl(var(--primary))] transition-colors">{{ $cat->name }}</a></li>
                    @endforeach
                    <li><a href="{{ route('account.orders') }}" class="hover:text-[hsl(var(--primary))] transition-colors">ติดตามคำสั่งซื้อ</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-slate-900 font-bold mb-6">ติดต่อเรา</h4>
                <ul class="space-y-4 text-sm text-slate-500">
                    <li class="flex items-start gap-3">
                        <x-heroicon-o-envelope class="w-[18px] h-[18px] text-[hsl(var(--primary))] mt-0.5 shrink-0" />
                        <span>Office@progressivemovement.in.th</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-heroicon-o-map-pin class="w-[18px] h-[18px] text-[hsl(var(--primary))] mt-0.5 shrink-0" />
                        <span>167 อาคารอนาคตใหม่ ชั้น 3 ยูนิตที่1 แขวงหัวหมาก เขตบางกะปิ กทม. 10240</span>
                    </li>
                </ul>
                <div class="flex gap-3 mt-6">
                    <a href="https://www.facebook.com/commonschoolth" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-[hsl(var(--primary))] hover:text-white transition-all">
                        <img src="{{ asset('images/social/facebook-logo-svgrepo-com.svg') }}" alt="Facebook" class="w-5 h-5">
                    </a>
                    <a href="https://www.instagram.com/pgmf_th?igsh=NzRrMnZsbmdsNmds" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-[hsl(var(--primary))] hover:text-white transition-all">
                        <img src="{{ asset('images/social/instagram-round-svgrepo-com.svg') }}" alt="Instagram" class="w-5 h-5">
                    </a>
                    <a href="https://www.youtube.com/@pgmfshop" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-[hsl(var(--primary))] hover:text-white transition-all">
                        <img src="{{ asset('images/social/youtube-round-svgrepo-com.svg') }}" alt="YouTube" class="w-5 h-5">
                    </a>
                    <a href="https://lin.ee/I2lbHqqa" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-[hsl(var(--primary))] hover:text-white transition-all">
                        <img src="{{ asset('images/social/line-logo-svgrepo-com.svg') }}" alt="LINE" class="w-5 h-5">
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-slate-200 pt-8 text-center">
            <p class="text-xs text-slate-400">&copy; {{ date('Y') }} Progressive Movement Foundation Shop. All rights reserved.</p>
        </div>
    </div>
</footer>
