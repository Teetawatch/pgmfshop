<div class="bg-slate-50 min-h-screen">
    <!-- Email Verification Success Alert -->
    @if($verified)
        <div class="bg-green-50 border-b border-green-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-green-800">
                            ยืนยันอีเมลสำเร็จ! บัญชีของคุณได้รับการยืนยันแล้ว
                        </p>
                        <p class="text-sm text-green-700 mt-1">
                            ขอบคุณที่ยืนยันอีเมล คุณสามารถใช้งานระบบได้เต็มรูปแบบแล้ว
                        </p>
                    </div>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="flex-shrink-0 text-green-600 hover:text-green-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @php
            session()->forget('verified');
        @endphp
    @endif

    <!-- Hero Banner Slider -->
    <header class="relative bg-white overflow-hidden">
        <div class="absolute inset-0 bg-linear-to-r from-orange-50 to-transparent pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
            @if($banners->count() > 0)
            <div x-data="bannerSlider({{ $banners->count() }})" x-init="startAutoSlide()" class="relative w-full rounded-3xl overflow-hidden shadow-2xl group">
                <!-- Slides -->
                <div class="relative w-full">
                    @foreach($banners as $idx => $banner)
                    <div x-show="current === {{ $idx }}"
                         x-transition:enter="transition ease-out duration-700"
                         x-transition:enter-start="opacity-0 scale-[1.02]"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="w-full">
                        <img src="{{ $banner->image }}" alt="{{ $banner->title }}" class="w-full h-auto object-contain">
                        @if($banner->title || $banner->subtitle || $banner->button_text)
                        <div class="absolute inset-0 bg-linear-to-r from-black/60 via-black/30 to-transparent"></div>
                        <div class="absolute inset-0 bg-linear-to-t from-black/40 via-transparent to-transparent"></div>
                        <div class="absolute inset-0 flex items-center">
                            <div class="px-6 sm:px-10 md:px-16 lg:px-24 text-white space-y-2 sm:space-y-3 md:space-y-4 max-w-xl">
                                @if($banner->title)
                                    <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs sm:text-sm font-medium border border-white/30">{{ $banner->subtitle ?: 'New Collection' }}</span>
                                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-6xl font-extrabold leading-tight">
                                        {{ $banner->title }}
                                    </h1>
                                @endif
                                @if($banner->button_text && $banner->button_link)
                                    <a href="{{ $banner->button_link }}" class="mt-2 sm:mt-4 inline-flex items-center gap-2 px-6 sm:px-8 py-2.5 sm:py-3 bg-white text-[hsl(var(--primary))] font-bold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 text-sm sm:text-base">
                                        {{ $banner->button_text }}
                                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                @if($banners->count() > 1)
                <!-- Navigation -->
                <div class="absolute bottom-4 sm:bottom-6 right-4 sm:right-8 flex items-center gap-2 z-10">
                    <button @click="prev()" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-black/20 backdrop-blur text-white hover:bg-white hover:text-[hsl(var(--primary))] transition-colors flex items-center justify-center">
                        <x-heroicon-o-chevron-left class="w-4 h-4 sm:w-5 sm:h-5" />
                    </button>
                    <div class="text-white font-medium text-xs sm:text-sm px-2 bg-black/20 rounded-full backdrop-blur">
                        <span x-text="current + 1"></span> / {{ $banners->count() }}
                    </div>
                    <button @click="next()" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-black/20 backdrop-blur text-white hover:bg-white hover:text-[hsl(var(--primary))] transition-colors flex items-center justify-center">
                        <x-heroicon-o-chevron-right class="w-4 h-4 sm:w-5 sm:h-5" />
                    </button>
                </div>
                @endif
            </div>
            @else
            {{-- Fallback: default banner when no banners uploaded --}}
            <div class="relative w-full rounded-3xl overflow-hidden shadow-2xl group">
                <div class="absolute inset-0 bg-[#C04918] flex items-center justify-center overflow-hidden">
                    <div class="absolute top-0 right-0 w-1/2 h-full bg-orange-600 -skew-x-12 translate-x-20 opacity-80"></div>
                    <div class="absolute bottom-0 left-0 w-1/3 h-2/3 bg-orange-700 rounded-full blur-3xl opacity-50"></div>
                    <div class="relative z-10 w-full h-full flex items-center justify-between px-6 sm:px-10 md:px-16 lg:px-24 py-16 sm:py-20 md:py-24">
                        <div class="max-w-xl text-white space-y-3 sm:space-y-4">
                            <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs sm:text-sm font-medium border border-white/30">ยินดีต้อนรับ</span>
                            <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight">
                                Progressive <br/> Movement Shop
                            </h1>
                            <p class="text-sm sm:text-base md:text-lg text-white/90 font-light max-w-md">
                                สินค้าคุณภาพ ราคาดี จัดส่งรวดเร็ว พร้อมโปรโมชั่นสุดพิเศษ
                            </p>
                            <a href="{{ route('products') }}" class="mt-2 sm:mt-4 inline-flex items-center gap-2 px-6 sm:px-8 py-2.5 sm:py-3 bg-white text-[hsl(var(--primary))] font-bold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 text-sm sm:text-base">
                                ช้อปเลย
                                <x-heroicon-o-arrow-right class="w-4 h-4" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </header>

    <script>
        function bannerSlider(total) {
            return {
                current: 0,
                count: total,
                interval: null,
                next() { this.current = (this.current + 1) % this.count; this.resetAutoSlide(); },
                prev() { this.current = (this.current - 1 + this.count) % this.count; this.resetAutoSlide(); },
                goTo(i) { this.current = i; this.resetAutoSlide(); },
                startAutoSlide() { this.interval = setInterval(() => { this.current = (this.current + 1) % this.count; }, 5000); },
                resetAutoSlide() { clearInterval(this.interval); this.startAutoSlide(); },
            }
        }
    </script>

    <!-- Product Tabs + Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <!-- Header: Tabs + Sort -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6 mb-8 sm:mb-10">
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mr-2 sm:mr-4">สินค้าทั้งหมด</h2>
                @foreach([['all', 'ทั้งหมด'], ['hot', 'HOT'], ['new', 'NEW'], ['recommended', 'RECOMMENDED']] as [$key, $label])
                    <button wire:click="setTab('{{ $key }}')"
                        class="px-4 sm:px-5 py-2 rounded-full text-xs sm:text-sm font-medium transition-all {{ $activeTab === $key ? 'bg-[hsl(var(--primary))] text-white shadow-lg shadow-[hsl(var(--primary))]/30 hover:scale-105' : 'bg-white border border-slate-200 text-slate-600 hover:border-[hsl(var(--primary))] hover:text-[hsl(var(--primary))]' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <select wire:model.live="sortBy" class="appearance-none bg-white border border-slate-200 text-slate-700 py-2 pl-4 pr-10 rounded-lg focus:ring-2 focus:ring-[hsl(var(--primary))] focus:border-transparent text-xs sm:text-sm cursor-pointer shadow-sm">
                        <option value="default">เรียงตาม: ค่าเริ่มต้น</option>
                        <option value="price_asc">ราคา: ต่ำ → สูง</option>
                        <option value="price_desc">ราคา: สูง → ต่ำ</option>
                        <option value="in_stock">สินค้าที่มีสต็อก</option>
                    </select>
                    <x-heroicon-o-chevron-down class="absolute right-3 top-2.5 pointer-events-none text-slate-400 w-4 h-4" />
                </div>
            </div>
        </div>

        <!-- Skeleton Loading -->
        <div wire:loading wire:target="setTab,sortBy">
            <x-skeleton type="product-grid" :count="8" />
        </div>

        <div wire:loading.remove wire:target="setTab,sortBy">
            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                @foreach($products->take($visibleCount) as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>

            @if($products->count() === 0)
                <div class="text-center py-20">
                    <x-heroicon-o-shopping-bag class="w-16 h-16 text-slate-300 mx-auto mb-4" />
                    <p class="text-slate-500 text-lg">ไม่พบสินค้า</p>
                </div>
            @endif

            <!-- Load More -->
            @if($visibleCount < $products->count())
                <div class="text-center mt-10 sm:mt-12">
                    <button wire:click="loadMore" class="px-8 sm:px-10 py-2.5 sm:py-3 rounded-full text-sm font-medium border border-slate-200 bg-white text-slate-700 hover:border-[hsl(var(--primary))] hover:text-[hsl(var(--primary))] hover:shadow-lg transition-all duration-300">
                        โหลดเพิ่มเติม
                    </button>
                </div>
            @endif
        </div>

        <!-- Payment & Shipping Info -->
        <div class="mt-16 sm:mt-20 border-t border-slate-200 pt-8 sm:pt-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex flex-col gap-2 text-center md:text-left">
                <h4 class="text-xs sm:text-sm font-semibold text-slate-500 uppercase tracking-wider">ช่องทางการชำระเงิน</h4>
                <div class="flex gap-4 items-center justify-center md:justify-start">
                    <img src="{{ vite_image('Thai_QR_Payment_Logo-01.jpg') }}" alt="Thai QR Payment" class="h-10 sm:h-12 object-contain rounded-lg shadow-sm">
                </div>
            </div>
            <div class="flex flex-col gap-2 text-center md:text-right">
                <h4 class="text-xs sm:text-sm font-semibold text-slate-500 uppercase tracking-wider">บริการจัดส่งสินค้า</h4>
                <div class="flex items-center justify-center md:justify-end gap-2">
                    <img src="{{ vite_image('ThailandPost_Logo.svg') }}" alt="ไปรษณีย์ไทย" class="h-10 sm:h-12 object-contain">
                </div>
            </div>
        </div>
    </main>
</div>
