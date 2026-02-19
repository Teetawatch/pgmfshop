@php $authUser = auth()->user(); @endphp

<header class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur-sm border-b border-gray-200/80"
    x-data="{ scrolled: false, lastScrollY: 0 }"
    x-init="lastScrollY = window.scrollY"
    @scroll.window.throttle.80ms="
        let y = window.scrollY;
        if (y > 100 && y > lastScrollY + 10) { scrolled = true }
        else if (y < lastScrollY - 10 || y <= 50) { scrolled = false }
        lastScrollY = y;
    ">
    <!-- Compact Bar (visible on scroll down, all screens) -->
    <div x-show="scrolled" x-cloak class="border-b-2 border-orange-500 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-11 md:h-12 gap-2 md:gap-4">
                <!-- Left: Home + Category Dropdown -->
                <div class="flex items-center gap-0.5 md:gap-1 shrink-0">
                    <a href="/" class="inline-flex items-center justify-center h-9 w-9 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="หน้าแรก">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </a>
                    <div class="relative" x-data="{ catOpen: false }" @click="catOpen = !catOpen" @click.away="catOpen = false" @mouseenter="if(window.innerWidth >= 768) catOpen = true" @mouseleave="if(window.innerWidth >= 768) catOpen = false">
                        <button class="flex items-center gap-1.5 md:gap-2 text-sm font-medium px-2 md:px-3 py-2 text-gray-700 hover:text-gray-900 transition-colors">
                            <svg class="h-4.5 w-4.5 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                            <span class="hidden sm:inline">หมวดหมู่สินค้า</span>
                        </button>
                        <div x-show="catOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute top-full left-0 pt-2 z-50" style="display: none;">
                            <div class="w-64 bg-white border border-gray-200 rounded-xl shadow-xl shadow-gray-200/60 py-2 overflow-hidden">
                                <p class="px-5 pt-1 pb-2.5 text-[11px] font-semibold uppercase tracking-wider text-gray-400">หมวดหมู่</p>
                                @foreach($categories as $cat)
                                    <a href="{{ route('products', ['category' => $cat->slug]) }}" class="flex items-center gap-3.5 px-5 py-3 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors group">
                                        <span class="w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200 flex items-center justify-center text-gray-500 text-xs font-bold shrink-0 transition-colors">
                                            {{ mb_substr($cat->name, 0, 1) }}
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium truncate">{{ $cat->name }}</p>
                                            @if($cat->products_count > 0)
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $cat->products_count }} สินค้า</p>
                                            @endif
                                        </div>
                                        <svg class="h-4 w-4 text-gray-300 group-hover:text-gray-500 transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                                    </a>
                                @endforeach
                                @if($categories->count() > 0)
                                    <div class="border-t border-gray-100 my-1.5 mx-4"></div>
                                    <a href="{{ route('products') }}" class="flex items-center gap-3.5 px-5 py-3 text-sm font-semibold text-gray-800 hover:bg-gray-50 transition-colors group">
                                        <span class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </span>
                                        ดูสินค้าทั้งหมด
                                        <svg class="h-4 w-4 text-gray-400 ml-auto shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Center: Search Bar -->
                <form wire:submit="search" class="flex flex-1 min-w-0 max-w-md" x-data @click.away="$wire.closeSuggestions()">
                    <div class="relative w-full">
                        <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="ค้นหา" @focus="if($wire.searchQuery.length >= 2) $wire.showSuggestions = true" class="w-full pl-3 md:pl-4 pr-9 md:pr-10 h-8 md:h-9 bg-gray-50 border border-gray-200 rounded-full text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-gray-400 focus:ring-1 focus:ring-gray-300 transition" />
                        <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 w-6 h-6 md:w-7 md:h-7 rounded-full bg-gray-800 flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <svg class="h-3 w-3 md:h-3.5 md:w-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </button>
                    </div>
                </form>

                <!-- Right: Actions -->
                <div class="flex items-center gap-0.5 md:gap-1 shrink-0">
                    <a href="{{ route('products') }}" class="hidden sm:inline-flex items-center justify-center h-9 w-9 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="สินค้าทั้งหมด">
                        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h7"/><circle cx="17" cy="18" r="3"/><path d="m21 21-1.5-1.5"/></svg>
                    </a>
                    @auth
                        <a href="{{ route('account') }}" class="inline-flex items-center justify-center h-9 w-9 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="บัญชีของฉัน">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center h-9 w-9 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="เข้าสู่ระบบ">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        </a>
                    @endauth
                    <a href="{{ route('cart') }}" class="relative inline-flex items-center justify-center h-9 w-9 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="ตะกร้าสินค้า">
                        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 002 1.58h9.78a2 2 0 001.95-1.57l1.65-7.43H5.12"/></svg>
                        @if($cartCount > 0)
                            <span class="absolute top-0 right-0 bg-gray-800 text-white text-[10px] rounded-full h-4 min-w-4 px-1 flex items-center justify-center font-medium leading-none">{{ $cartCount }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Bar -->
    <div x-show="!scrolled">
    <div class="container mx-auto px-4">
        <div class="flex h-16 items-center justify-between gap-6">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2.5 shrink-0 group">
                <img src="{{ asset('images/pgmf-logo.jpg') }}" alt="PGMF Shop" class="h-9 w-9 rounded-lg object-cover ring-1 ring-gray-200 group-hover:ring-gray-300 transition" />
                <span class="font-bold text-lg text-gray-900 hidden sm:inline tracking-tight">PGMF Shop</span>
            </a>

            <!-- Search Bar - Desktop -->
            <form wire:submit="search" class="hidden md:flex flex-1 justify-end max-w-lg ml-auto" x-data @click.away="$wire.closeSuggestions()">
                <div class="relative w-72">
                    <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="ค้นหาสินค้า..." @focus="if($wire.searchQuery.length >= 2) $wire.showSuggestions = true" class="w-full pl-4 pr-10 h-9 bg-gray-50 border border-gray-200 rounded-full text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-gray-400 focus:ring-1 focus:ring-gray-300 transition" />
                    <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full bg-gray-800 flex items-center justify-center hover:bg-gray-700 transition-colors">
                        <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </button>

                    <!-- Autocomplete Dropdown -->
                    @if($showSuggestions && count($searchSuggestions) > 0)
                        <div class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-xl shadow-xl shadow-gray-200/60 overflow-hidden z-50">
                            @foreach($searchSuggestions as $suggestion)
                                @php $imgs = is_array($suggestion['images']) ? $suggestion['images'] : json_decode($suggestion['images'] ?? '[]', true); @endphp
                                <a href="{{ route('products.show', $suggestion['slug']) }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden shrink-0">
                                        @if(!empty($imgs))
                                            <img src="{{ $imgs[0] }}" alt="" class="w-full h-full object-cover" />
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $suggestion['name'] }}</p>
                                        <p class="text-xs text-teal-600 font-semibold">฿{{ number_format($suggestion['price'], 0) }}</p>
                                    </div>
                                </a>
                            @endforeach
                            <a href="{{ route('products', ['search' => $searchQuery]) }}" class="block px-4 py-2.5 text-center text-sm font-medium text-teal-600 hover:bg-gray-50 border-t border-gray-100 transition-colors">
                                ดูผลลัพธ์ทั้งหมด
                            </a>
                        </div>
                    @elseif($showSuggestions && strlen(trim($searchQuery)) >= 2 && count($searchSuggestions) === 0)
                        <div class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-xl shadow-xl shadow-gray-200/60 overflow-hidden z-50">
                            <div class="px-4 py-6 text-center text-sm text-gray-400">ไม่พบสินค้าที่ค้นหา</div>
                        </div>
                    @endif
                </div>
            </form>

            <!-- Right Actions -->
            <div class="flex items-center gap-2">
                @auth
                    <!-- Wishlist -->
                    <a href="{{ route('account.wishlist') }}" class="relative inline-flex items-center justify-center h-10 w-10 rounded-lg text-gray-500 hover:text-red-500 hover:bg-red-50 transition-colors" title="รายการโปรด">
                        <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                        @if($wishlistCount > 0)
                            <span class="absolute top-1 right-1 bg-red-500 text-white text-[10px] rounded-full h-4 min-w-4 px-1 flex items-center justify-center font-medium leading-none">{{ $wishlistCount }}</span>
                        @endif
                    </a>

                    <!-- Cart -->
                    <a href="{{ route('cart') }}" class="relative inline-flex items-center justify-center h-10 w-10 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 002 1.58h9.78a2 2 0 001.95-1.57l1.65-7.43H5.12"/></svg>
                        @if($cartCount > 0)
                            <span class="absolute top-1 right-1 bg-gray-800 text-white text-[10px] rounded-full h-4 min-w-4 px-1 flex items-center justify-center font-medium leading-none">{{ $cartCount }}</span>
                        @endif
                    </a>

                    <!-- User Dropdown - Desktop -->
                    <div class="hidden md:block relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 h-10 pl-1 pr-3 rounded-lg hover:bg-gray-100 transition-colors">
                            @if($authUser->social_avatar || $authUser->avatar)
                                <img src="{{ $authUser->social_avatar ?: $authUser->avatar }}" alt="{{ $authUser->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-gray-100" referrerpolicy="no-referrer" />
                            @else
                                <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-white text-xs font-semibold ring-2 ring-gray-100">
                                    {{ strtoupper(mb_substr($authUser->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="text-sm font-medium text-gray-700 max-w-[100px] truncate">{{ $authUser->name }}</span>
                            <svg class="h-3.5 w-3.5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" class="absolute right-0 top-full mt-2 w-64 z-50" style="display: none;">
                            <div class="bg-white rounded-xl border border-gray-200 shadow-lg shadow-gray-200/50 overflow-hidden">
                                <!-- User Info -->
                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                                    <div class="flex items-center gap-3">
                                        @if($authUser->social_avatar || $authUser->avatar)
                                            <img src="{{ $authUser->social_avatar ?: $authUser->avatar }}" alt="{{ $authUser->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white" referrerpolicy="no-referrer" />
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white text-sm font-semibold ring-2 ring-white">
                                                {{ strtoupper(mb_substr($authUser->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $authUser->name }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $authUser->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-1.5">
                                    <a href="{{ route('account') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        บัญชีของฉัน
                                    </a>
                                    <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        คำสั่งซื้อ
                                    </a>
                                    <a href="{{ route('account.wishlist') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                                        <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                        รายการโปรด
                                        @if($wishlistCount > 0)
                                            <span class="ml-auto bg-red-100 text-red-600 text-[10px] font-semibold px-1.5 py-0.5 rounded-full">{{ $wishlistCount }}</span>
                                        @endif
                                    </a>
                                    <a href="{{ route('account.addresses') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        ที่อยู่จัดส่ง
                                    </a>
                                    @if($authUser->role === 'admin')
                                        <a href="/admin" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                            แผงควบคุมแอดมิน
                                        </a>
                                    @endif
                                </div>

                                <div class="border-t border-gray-100">
                                    <button wire:click="logout" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors w-full">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                        ออกจากระบบ
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Cart (Guest) -->
                    <a href="{{ route('cart') }}" class="relative inline-flex items-center justify-center h-10 w-10 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 002 1.58h9.78a2 2 0 001.95-1.57l1.65-7.43H5.12"/></svg>
                        @if($cartCount > 0)
                            <span class="absolute top-1 right-1 bg-gray-800 text-white text-[10px] rounded-full h-4 min-w-4 px-1 flex items-center justify-center font-medium leading-none">{{ $cartCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('login') }}" class="hidden md:inline-flex items-center gap-2 h-9 px-4 rounded-lg text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        เข้าสู่ระบบ
                    </a>
                @endauth

                <!-- Mobile Menu Toggle -->
                <button wire:click="$toggle('isMenuOpen')" class="md:hidden inline-flex items-center justify-center h-10 w-10 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    @if($isMenuOpen)
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    @else
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                    @endif
                </button>
            </div>
        </div>
    </div>
    </div>

    <!-- Category Nav Bar - Desktop -->
    <div x-show="!scrolled" class="border-b-2 border-orange-500 bg-white hidden md:block">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-11 text-sm">
                <!-- Left: Category Dropdown -->
                <div class="flex items-center">
                    <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="flex items-center gap-2 text-sm font-medium px-3 py-2 text-gray-700 hover:text-gray-900 transition-colors">
                            <svg class="h-4.5 w-4.5 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                            <span>หมวดหมู่สินค้า</span>
                            <svg class="h-3.5 w-3.5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute top-full left-0 pt-2 z-50" style="display: none;">
                            <div class="w-64 bg-white border border-gray-200 rounded-xl shadow-xl shadow-gray-200/60 py-2 overflow-hidden">
                                <p class="px-5 pt-1 pb-2.5 text-[11px] font-semibold uppercase tracking-wider text-gray-400">หมวดหมู่</p>
                                @foreach($categories as $cat)
                                    <a href="{{ route('products', ['category' => $cat->slug]) }}" class="flex items-center gap-3.5 px-5 py-3 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors group">
                                        <span class="w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200 flex items-center justify-center text-gray-500 text-xs font-bold shrink-0 transition-colors">
                                            {{ mb_substr($cat->name, 0, 1) }}
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium truncate">{{ $cat->name }}</p>
                                            @if($cat->products_count > 0)
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $cat->products_count }} สินค้า</p>
                                            @endif
                                        </div>
                                        <svg class="h-4 w-4 text-gray-300 group-hover:text-gray-500 transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                                    </a>
                                @endforeach
                                @if($categories->count() > 0)
                                    <div class="border-t border-gray-100 my-1.5 mx-4"></div>
                                    <a href="{{ route('products') }}" class="flex items-center gap-3.5 px-5 py-3 text-sm font-semibold text-gray-800 hover:bg-gray-50 transition-colors group">
                                        <span class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </span>
                                        ดูสินค้าทั้งหมด
                                        <svg class="h-4 w-4 text-gray-400 ml-auto shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Center: Category Quick Links -->
                <div class="flex items-center gap-1">
                    @foreach($categories as $cat)
                        <a href="{{ route('products', ['category' => $cat->slug]) }}" class="px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>

                <!-- Right: Navigation Links -->
                <div class="flex items-center gap-1">
                    <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="flex items-center gap-1.5 px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
                            <span>เกี่ยวกับร้าน</span>
                            <svg class="h-3 w-3 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute top-full right-0 pt-2 z-50" style="display: none;">
                            <div class="w-48 bg-white border border-gray-200 rounded-xl shadow-xl shadow-gray-200/60 py-1.5 overflow-hidden">
                                <a href="{{ route('about') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">รายละเอียดร้าน</a>
                                <a href="{{ route('faq') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">คำถามที่พบบ่อย</a>
                                <a href="{{ route('how-to-order') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">วิธีการสั่งซื้อ</a>
                                <a href="{{ route('privacy') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">นโยบายความเป็นส่วนตัว</a>
                            </div>
                        </div>
                    </div>
                    @auth
                        <a href="{{ route('account.orders') }}" class="px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">การสั่งซื้อของฉัน</a>
                    @endauth
                    <a href="#footer" class="px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">ติดต่อร้านค้า</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    @if($isMenuOpen)
        <div class="md:hidden border-t border-gray-200 bg-white">
            <div class="container mx-auto px-4 py-4 space-y-4">
                <!-- Mobile Search -->
                <form wire:submit="search">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" wire:model="searchQuery" placeholder="ค้นหาสินค้า..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-gray-300 focus:ring-1 focus:ring-gray-300 transition" />
                    </div>
                </form>

                <!-- Mobile Nav Links -->
                <div class="space-y-0.5">
                    <a href="{{ route('products') }}" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </span>
                        สินค้าทั้งหมด
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('products', ['category' => $cat->slug]) }}" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-xs font-semibold text-gray-500">{{ mb_substr($cat->name, 0, 1) }}</span>
                            <div>
                                <p>{{ $cat->name }}</p>
                                @if($cat->products_count > 0)
                                    <p class="text-[11px] text-gray-400">{{ $cat->products_count }} สินค้า</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Mobile Auth -->
                <div class="border-t border-gray-100 pt-4 space-y-1">
                    @auth
                        <a href="{{ route('account') }}" class="flex items-center gap-3 py-2.5 px-3 rounded-lg hover:bg-gray-50 transition-colors">
                            @if($authUser->social_avatar || $authUser->avatar)
                                <img src="{{ $authUser->social_avatar ?: $authUser->avatar }}" alt="{{ $authUser->name }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-100" referrerpolicy="no-referrer" />
                            @else
                                <div class="w-9 h-9 rounded-full bg-gray-700 flex items-center justify-center text-white text-xs font-semibold ring-2 ring-gray-100">
                                    {{ strtoupper(mb_substr($authUser->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $authUser->name }}</p>
                                <p class="text-xs text-gray-500">{{ $authUser->email }}</p>
                            </div>
                        </a>
                        <a href="{{ route('account.orders') }}" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <svg class="h-4 w-4 text-gray-400 ml-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            คำสั่งซื้อ
                        </a>
                        <a href="{{ route('account.wishlist') }}" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <svg class="h-4 w-4 text-gray-400 ml-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                            รายการโปรด
                            @if($wishlistCount > 0)
                                <span class="bg-red-100 text-red-600 text-[10px] font-semibold px-1.5 py-0.5 rounded-full">{{ $wishlistCount }}</span>
                            @endif
                        </a>
                        @if($authUser->role === 'admin')
                            <a href="/admin" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                <svg class="h-4 w-4 text-gray-400 ml-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                แผงควบคุมแอดมิน
                            </a>
                        @endif
                        <button wire:click="logout" class="flex items-center gap-3 py-2.5 px-3 rounded-lg text-sm text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors w-full">
                            <svg class="h-4 w-4 ml-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            ออกจากระบบ
                        </button>
                    @else
                        <div class="flex gap-2 px-3">
                            <a href="{{ route('login') }}" class="flex-1">
                                <button class="w-full py-2.5 px-4 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">เข้าสู่ระบบ</button>
                            </a>
                            <a href="{{ route('register') }}" class="flex-1">
                                <button class="w-full py-2.5 px-4 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">สมัครสมาชิก</button>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    @endif
</header>
