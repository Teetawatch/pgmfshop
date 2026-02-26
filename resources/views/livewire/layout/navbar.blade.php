@php $authUser = auth()->user(); @endphp

<header
    x-data="{
        scrolled: false,
        menuOpen: false,
        init() {
            this.scrolled = window.scrollY > 80;
            let ticking = false;
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        if (this.scrolled) {
                            this.scrolled = window.scrollY > 30;
                        } else {
                            this.scrolled = window.scrollY > 80;
                        }
                        ticking = false;
                    });
                    ticking = true;
                }
            }, { passive: true });
        }
    }"
    class="sticky top-0 z-50 w-full bg-white backdrop-blur-md mt-0" style="border:none;box-shadow:none">

    {{-- ===== Main Bar ===== --}}
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
        <div class="flex items-center justify-between gap-1 sm:gap-2 h-14 sm:h-16">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2 shrink-0 group">
                <img src="{{ asset('images/pgmf-logo.jpg') }}" alt="PGMF Shop"
                    class="h-8 w-8 sm:h-9 sm:w-9 rounded-lg object-cover ring-1 ring-slate-200 group-hover:ring-[hsl(var(--primary))] transition-all duration-300" />
                <span class="font-bold text-slate-900 hidden sm:inline tracking-tight group-hover:text-[hsl(var(--primary))] transition-all duration-300 text-base sm:text-lg">PGMF Shop</span>
            </a>

            {{-- Category Links - Desktop --}}
            <div class="hidden md:flex items-center gap-1 text-sm font-medium text-slate-700">
                {{-- Categories Dropdown --}}
                <div class="relative" x-data="{ open: false, timer: null }" @mouseenter="clearTimeout(timer); open = true" @mouseleave="timer = setTimeout(() => open = false, 150)">
                    <button class="flex items-center gap-1.5 px-3 py-3 rounded-lg hover:text-[hsl(var(--primary))] hover:bg-slate-50 transition-colors">
                        <x-heroicon-o-bars-3 class="h-4 w-4" />
                        <span>หมวดหมู่</span>
                        <x-heroicon-o-chevron-down class="h-3.5 w-3.5 text-slate-400 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute top-full left-0 z-50" style="display:none">
                        <div class="pt-2">
                            <div class="w-64 bg-white border border-slate-200 rounded-xl shadow-xl py-2 overflow-hidden">
                                <p class="px-5 pt-1 pb-2.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400">หมวดหมู่</p>
                                @foreach($categories as $cat)
                                    <a href="{{ route('products', ['category' => $cat->slug]) }}" class="flex items-center gap-3.5 px-5 py-3 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors group">
                                        <span class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-slate-200 flex items-center justify-center text-slate-500 text-xs font-bold shrink-0 transition-colors">{{ mb_substr($cat->name, 0, 1) }}</span>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium truncate">{{ $cat->name }}</p>
                                            @if($cat->products_count > 0)<p class="text-xs text-slate-400 mt-0.5">{{ $cat->products_count }} สินค้า</p>@endif
                                        </div>
                                        <x-heroicon-o-chevron-right class="h-4 w-4 text-slate-300 group-hover:text-slate-500 transition-colors shrink-0" />
                                    </a>
                                @endforeach
                                @if($categories->count() > 0)
                                    <div class="border-t border-slate-100 my-1.5 mx-4"></div>
                                    <a href="{{ route('products') }}" class="flex items-center gap-3.5 px-5 py-3 text-sm font-semibold text-slate-800 hover:bg-slate-50 transition-colors group">
                                        <span class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center"><x-heroicon-o-cube class="h-4 w-4 text-white" /></span>
                                        ดูสินค้าทั้งหมด
                                        <x-heroicon-o-arrow-right class="h-4 w-4 text-slate-400 ml-auto shrink-0" />
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Spacer --}}
            <div class="flex-1"></div>

            {{-- About Dropdown --}}
            <div class="hidden md:flex items-center gap-1">
                <div class="relative" x-data="{ open: false, timer: null }" @mouseenter="clearTimeout(timer); open = true" @mouseleave="timer = setTimeout(() => open = false, 150)">
                    <button class="flex items-center gap-1 px-3 py-3 rounded-lg text-sm text-slate-600 hover:text-[hsl(var(--primary))] hover:bg-slate-50 transition-colors">
                        <span>เกี่ยวกับร้าน</span>
                        <x-heroicon-o-chevron-down class="h-3 w-3 text-slate-400 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute top-full left-0 z-50" style="display:none">
                        <div class="pt-2">
                            <div class="w-48 bg-white border border-slate-200 rounded-xl shadow-xl  py-1.5 overflow-hidden">
                                <a href="{{ route('about') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">รายละเอียดร้าน</a>
                                <a href="{{ route('faq') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">คำถามที่พบบ่อย</a>
                                <a href="{{ route('how-to-order') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">วิธีการสั่งซื้อ</a>
                                <a href="{{ route('privacy') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">นโยบายความเป็นส่วนตัว</a>
                                @auth
                                    <div class="border-t border-slate-100 my-1"></div>
                                    <a href="{{ route('account.orders') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">การสั่งซื้อของฉัน</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('account.orders') }}" class="flex items-center gap-1 px-3 py-3 rounded-lg text-sm text-slate-600 hover:text-[hsl(var(--primary))] hover:bg-slate-50 transition-colors">
                    <span>การสั่งซื้อของฉัน</span>
                </a>
                <a href="{{ route('contact') }}" class="flex items-center gap-1 px-3 py-3 rounded-lg text-sm text-slate-600 hover:text-[hsl(var(--primary))] hover:bg-slate-50 transition-colors">
                    <span>ติดต่อร้านค้า</span>
                </a>
            </div>

            {{-- Search Bar --}}
            <form wire:submit="search" class="flex flex-1 min-w-0 max-w-[calc(100%-80px)] mx-2 md:flex-none md:max-w-none md:w-[220px] lg:w-[280px]" x-data @click.away="$wire.closeSuggestions()">
                <div class="relative w-full">
                    <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="ค้นหาสินค้า..."
                        @focus="if($wire.searchQuery.length >= 2) $wire.showSuggestions = true"
                        class="w-full pl-4 pr-10 bg-slate-100 border-none rounded-full text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[hsl(var(--primary))] transition-all duration-300 h-10" />
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[hsl(var(--primary))] transition-colors">
                        <x-heroicon-o-magnifying-glass class="h-4 w-4" />
                    </button>
                    @if($showSuggestions && count($searchSuggestions) > 0)
                        <div class="absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-xl shadow-xl  overflow-hidden z-50">
                            @foreach($searchSuggestions as $suggestion)
                                @php $imgs = is_array($suggestion['images']) ? $suggestion['images'] : json_decode($suggestion['images'] ?? '[]', true); @endphp
                                <a href="{{ route('products.show', $suggestion['slug']) }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 transition-colors">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 overflow-hidden shrink-0">
                                        @if(!empty($imgs))<img src="{{ $imgs[0] }}" alt="" class="w-full h-full object-cover" />@endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-800 truncate">{{ $suggestion['name'] }}</p>
                                        <p class="text-xs text-[hsl(var(--primary))] font-semibold">฿{{ number_format($suggestion['price'], 0) }}</p>
                                    </div>
                                </a>
                            @endforeach
                            <a href="{{ route('products', ['search' => $searchQuery]) }}" class="block px-4 py-2.5 text-center text-sm font-medium text-[hsl(var(--primary))] hover:bg-slate-50 border-t border-slate-100 transition-colors">ดูผลลัพธ์ทั้งหมด</a>
                        </div>
                    @elseif($showSuggestions && strlen(trim($searchQuery)) >= 2 && count($searchSuggestions) === 0)
                        <div class="absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-xl shadow-xl  overflow-hidden z-50">
                            <div class="px-4 py-6 text-center text-sm text-slate-400">ไม่พบสินค้าที่ค้นหา</div>
                        </div>
                    @endif
                </div>
            </form>

            {{-- Right Actions --}}
            <div class="flex items-center gap-0.5 sm:gap-1.5 shrink-0 border-l border-slate-200 pl-3 ml-1">
                <a href="{{ route('account.wishlist') }}" class="relative p-2 rounded-full text-slate-600 hover:bg-slate-100 transition-colors" title="รายการโปรด">
                        <x-heroicon-o-heart class="h-5 w-5" />
                        @if($wishlistCount > 0)<span class="absolute top-0.5 right-0.5 w-4 h-4 bg-[hsl(var(--primary))] text-white text-[10px] flex items-center justify-center rounded-full font-bold">{{ $wishlistCount }}</span>@endif
                    </a>
                    <a href="{{ route('cart') }}" class="relative p-2 rounded-full text-slate-600 hover:bg-slate-100 transition-colors">
                        <x-heroicon-o-shopping-cart class="h-5 w-5" />
                        @if($cartCount > 0)<span class="absolute top-0.5 right-0.5 w-4 h-4 bg-[hsl(var(--primary))] text-white text-[10px] flex items-center justify-center rounded-full font-bold">{{ $cartCount }}</span>@endif
                    </a>
                    @auth   
                    {{-- User Dropdown - Desktop --}}
                    <div class="hidden md:block relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 pl-2 hover:opacity-80 transition-opacity">
                            @if($authUser->social_avatar || $authUser->avatar)
                                <img src="{{ $authUser->social_avatar ?: $authUser->avatar }}" alt="{{ $authUser->name }}" class="w-8 h-8 rounded-full object-cover border border-slate-200" referrerpolicy="no-referrer" />
                            @else
                                <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-white text-xs font-semibold border border-slate-200">{{ strtoupper(mb_substr($authUser->name, 0, 1)) }}</div>
                            @endif
                            <span class="hidden xl:block text-sm font-medium text-slate-700 max-w-[90px] truncate">{{ $authUser->name }}</span>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" class="absolute right-0 top-full mt-2 w-64 z-50" style="display:none">
                            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                                <div class="px-4 py-3 bg-slate-50 border-b border-slate-100">
                                    <div class="flex items-center gap-3">
                                        @if($authUser->social_avatar || $authUser->avatar)
                                            <img src="{{ $authUser->social_avatar ?: $authUser->avatar }}" alt="{{ $authUser->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white" referrerpolicy="no-referrer" />
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-white text-sm font-semibold ring-2 ring-white">{{ strtoupper(mb_substr($authUser->name, 0, 1)) }}</div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-slate-900 truncate">{{ $authUser->name }}</p>
                                            <p class="text-xs text-slate-500 truncate">{{ $authUser->email }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-1.5">
                                    <a href="{{ route('account') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors"><x-heroicon-o-user class="h-4 w-4 text-slate-400" />บัญชีของฉัน</a>
                                    <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors"><x-heroicon-o-cube class="h-4 w-4 text-slate-400" />คำสั่งซื้อ</a>
                                    <a href="{{ route('account.wishlist') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors"><x-heroicon-o-heart class="h-4 w-4 text-slate-400" />รายการโปรด@if($wishlistCount > 0)<span class="ml-auto bg-red-100 text-red-600 text-[10px] font-semibold px-1.5 py-0.5 rounded-full">{{ $wishlistCount }}</span>@endif</a>
                                    <a href="{{ route('account.addresses') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors"><x-heroicon-o-map-pin class="h-4 w-4 text-slate-400" />ที่อยู่จัดส่ง</a>
                                    @if($authUser->role === 'admin')
                                        <a href="/admin" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors"><x-heroicon-o-shield-check class="h-4 w-4 text-slate-400" />แผงควบคุมแอดมิน</a>
                                    @endif
                                </div>
                                <div class="border-t border-slate-100">
                                    <button wire:click="logout" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors w-full"><x-heroicon-o-arrow-right-on-rectangle class="h-4 w-4" />ออกจากระบบ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden md:inline-flex items-center gap-2 h-9 px-4 rounded-full text-sm font-medium text-slate-600 border border-slate-200 hover:bg-slate-50 hover:text-slate-800 transition-colors"><x-heroicon-o-user class="h-4 w-4" />เข้าสู่ระบบ</a>
                @endauth
                {{-- Mobile Menu Toggle --}}
                <button @click="menuOpen = !menuOpen" class="md:hidden p-2 rounded-full text-slate-600 hover:bg-slate-100 transition-colors" title="เมนู">
                    <x-heroicon-o-x-mark class="h-5 w-5" x-show="menuOpen" style="display:none" />
                    <x-heroicon-o-bars-3 class="h-5 w-5" x-show="!menuOpen" />
                </button>
            </div>
        </div>
    </div>

    
    {{-- ===== Mobile Menu ===== --}}
    <div class="md:hidden bg-white"
         x-show="menuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         style="display:none">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 space-y-3">
                <div class="space-y-0.5">
                    <a href="{{ route('products') }}" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                        <span class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center"><x-heroicon-o-cube class="h-4 w-4 text-white" /></span>
                        สินค้าทั้งหมด
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('products', ['category' => $cat->slug]) }}" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            <span class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-500">{{ mb_substr($cat->name, 0, 1) }}</span>
                            <div>
                                <p>{{ $cat->name }}</p>
                                @if($cat->products_count > 0)<p class="text-[11px] text-slate-400">{{ $cat->products_count }} สินค้า</p>@endif
                            </div>
                        </a>
                    @endforeach
                </div>
                {{-- About + Contact --}}
                <div class="border-t border-slate-100 pt-3 space-y-0.5">
                    <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400">เกี่ยวกับร้าน</p>
                    <a href="{{ route('about') }}" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        <x-heroicon-o-information-circle class="h-4 w-4 text-slate-400 ml-0.5" />รายละเอียดร้าน
                    </a>
                    <a href="{{ route('how-to-order') }}" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        <x-heroicon-o-clipboard-document-check class="h-4 w-4 text-slate-400 ml-0.5" />วิธีการสั่งซื้อ
                    </a>
                    <a href="{{ route('faq') }}" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        <x-heroicon-o-question-mark-circle class="h-4 w-4 text-slate-400 ml-0.5" />คำถามที่พบบ่อย
                    </a>
                    <a href="{{ route('contact') }}" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        <x-heroicon-o-phone class="h-4 w-4 text-slate-400 ml-0.5" />ติดต่อร้านค้า
                    </a>
                    @auth
                        <a href="{{ route('account.orders') }}" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            <x-heroicon-o-cube class="h-4 w-4 text-slate-400 ml-0.5" />การสั่งซื้อของฉัน
                        </a>
                    @endauth
                </div>

                <div class="border-t border-slate-100 pt-3 space-y-1">
                    @auth
                        <a href="{{ route('account') }}" class="flex items-center gap-3 py-2.5 px-3 rounded-lg hover:bg-slate-50 transition-colors">
                            @if($authUser->social_avatar || $authUser->avatar)
                                <img src="{{ $authUser->social_avatar ?: $authUser->avatar }}" alt="{{ $authUser->name }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-slate-100" referrerpolicy="no-referrer" />
                            @else
                                <div class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center text-white text-xs font-semibold ring-2 ring-slate-100">{{ strtoupper(mb_substr($authUser->name, 0, 1)) }}</div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-slate-900">{{ $authUser->name }}</p>
                                <p class="text-xs text-slate-500">{{ $authUser->email }}</p>
                            </div>
                        </a>
                        <a href="{{ route('account.wishlist') }}" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors"><x-heroicon-o-heart class="h-4 w-4 text-slate-400 ml-0.5" />รายการโปรด@if($wishlistCount > 0)<span class="bg-red-100 text-red-600 text-[10px] font-semibold px-1.5 py-0.5 rounded-full">{{ $wishlistCount }}</span>@endif</a>
                        @if($authUser->role === 'admin')
                            <a href="/admin" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors"><x-heroicon-o-shield-check class="h-4 w-4 text-slate-400 ml-0.5" />แผงควบคุมแอดมิน</a>
                        @endif
                        <button wire:click="logout" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition-colors w-full"><x-heroicon-o-arrow-right-on-rectangle class="h-4 w-4 ml-0.5" />ออกจากระบบ</button>
                    @else
                        <div class="flex gap-2 px-3">
                            <a href="{{ route('login') }}" class="flex-1"><button class="w-full py-2.5 px-4 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">เข้าสู่ระบบ</button></a>
                            <a href="{{ route('register') }}" class="flex-1"><button class="w-full py-2.5 px-4 bg-[hsl(var(--primary))] text-white rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">สมัครสมาชิก</button></a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
</header>
