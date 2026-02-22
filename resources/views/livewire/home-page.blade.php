<div class="bg-gray-50 min-h-screen">
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
            // Clear the session variable to prevent showing again
            session()->forget('verified');
        @endphp
    @endif
    <!-- Hero Banner Slider -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-2">
        @if($banners->count() > 0)
        <div x-data="bannerSlider({{ $banners->count() }})" x-init="startAutoSlide()" class="relative rounded-2xl overflow-hidden h-[200px] sm:h-[320px] md:h-[420px] lg:h-[480px] group shadow-lg shadow-gray-300/40">
            <!-- Slides -->
            <div class="relative w-full h-full">
                @foreach($banners as $idx => $banner)
                <div x-show="current === {{ $idx }}"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 scale-[1.02]"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0">
                    <img src="{{ $banner->image }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-linear-to-r from-black/60 via-black/30 to-transparent"></div>
                    <div class="absolute inset-0 bg-linear-to-t from-black/40 via-transparent to-transparent"></div>
                    @if($banner->title || $banner->subtitle || $banner->button_text)
                    <div class="absolute inset-0 flex items-center">
                        <div class="px-5 sm:px-8 md:px-12 text-white space-y-2 sm:space-y-3 md:space-y-4 max-w-xl">
                            @if($banner->title)
                                <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight tracking-tight drop-shadow-lg">
                                    {{ $banner->title }}
                                </h2>
                            @endif
                            @if($banner->subtitle)
                                <p class="text-xs sm:text-sm md:text-base text-white/90 max-w-md leading-relaxed drop-shadow">{{ $banner->subtitle }}</p>
                            @endif
                            @if($banner->button_text && $banner->button_link)
                                <a href="{{ $banner->button_link }}" class="inline-flex items-center gap-2 bg-white text-gray-900 hover:bg-gray-100 text-xs sm:text-sm px-5 sm:px-6 py-2 sm:py-2.5 rounded-lg font-semibold mt-1 transition-all shadow-lg hover:shadow-xl group/btn">
                                    {{ $banner->button_text }}
                                    <x-heroicon-o-arrow-right class="w-3.5 h-3.5 transition-transform group-hover/btn:translate-x-0.5" />
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            @if($banners->count() > 1)
            <!-- Prev/Next Arrows -->
            <button @click="prev()" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 w-9 h-9 sm:w-10 sm:h-10 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-lg sm:opacity-0 sm:group-hover:opacity-100 transition-all duration-300 z-10 hover:scale-105 active:scale-95">
                <x-heroicon-o-chevron-left class="w-4 h-4 sm:w-5 sm:h-5 text-gray-700" />
            </button>
            <button @click="next()" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 w-9 h-9 sm:w-10 sm:h-10 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-lg sm:opacity-0 sm:group-hover:opacity-100 transition-all duration-300 z-10 hover:scale-105 active:scale-95">
                <x-heroicon-o-chevron-right class="w-4 h-4 sm:w-5 sm:h-5 text-gray-700" />
            </button>

            <!-- Slide Counter + Dots -->
            <div class="absolute bottom-3 sm:bottom-4 left-0 right-0 flex items-center justify-center gap-3 z-10 px-4">
                <!-- Dots -->
                <div class="flex gap-1.5 sm:gap-2">
                    @foreach($banners as $idx => $banner)
                    <button @click="goTo({{ $idx }})" :class="current === {{ $idx }} ? 'bg-white w-5 sm:w-7' : 'bg-white/40 w-2 sm:w-2.5 hover:bg-white/60'"
                        class="h-2 sm:h-2.5 rounded-full transition-all duration-300"></button>
                    @endforeach
                </div>
                <!-- Counter -->
                <span class="hidden sm:inline-flex items-center gap-1 text-[11px] text-white/80 font-medium bg-black/30 backdrop-blur-sm px-2.5 py-1 rounded-full">
                    <span x-text="current + 1"></span>/<span>{{ $banners->count() }}</span>
                </span>
            </div>
            @endif
        </div>
        @else
        {{-- Fallback: default banner when no banners uploaded --}}
        <div class="relative rounded-2xl overflow-hidden h-[200px] sm:h-[320px] md:h-[420px] lg:h-[480px] shadow-lg shadow-gray-300/40">
            <div class="absolute inset-0 bg-linear-to-br from-orange-500 via-orange-600 to-red-600"></div>
            <!-- Decorative circles -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-white/10 rounded-full"></div>
            <div class="absolute top-1/2 right-1/4 w-32 h-32 bg-white/5 rounded-full"></div>
            <div class="absolute inset-0 flex items-center">
                <div class="px-6 sm:px-10 md:px-12 z-10 text-white space-y-3 sm:space-y-4 max-w-lg">
                    <span class="inline-block text-[10px] sm:text-xs font-bold uppercase tracking-widest bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">ยินดีต้อนรับ</span>
                    <h2 class="text-2xl sm:text-4xl md:text-5xl font-extrabold leading-tight tracking-tight">PGMF SHOP</h2>
                    <p class="text-sm sm:text-base text-white/90 max-w-sm leading-relaxed">สินค้าคุณภาพ ราคาดี จัดส่งรวดเร็ว พร้อมโปรโมชั่นสุดพิเศษ</p>
                    <a href="{{ route('products') }}" class="inline-flex items-center gap-2 bg-white text-orange-600 hover:bg-gray-100 text-xs sm:text-sm px-6 py-2.5 rounded-lg font-semibold transition-all shadow-lg hover:shadow-xl group/btn">
                        ช้อปเลย
                        <x-heroicon-o-arrow-right class="w-3.5 h-3.5 transition-transform group-hover/btn:translate-x-0.5" />
                    </a>
                </div>
            </div>
        </div>
        @endif
    </section>

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
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Tabs -->
        <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
            <div class="flex items-center gap-1">
                <h2 class="text-base sm:text-lg font-bold text-gray-800 mr-3">สินค้าทั้งหมด</h2>
                <div class="flex gap-1">
                    @foreach([['all', 'สินค้าทั้งหมด'], ['hot', 'HOT'], ['new', 'NEW'], ['recommended', 'RECOMMENDED']] as [$key, $label])
                        <button wire:click="setTab('{{ $key }}')"
                            class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors {{ $activeTab === $key ? 'bg-[hsl(var(--primary))] text-white' : 'bg-white text-gray-500 hover:bg-gray-100 border border-gray-200' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="flex items-center gap-2">
                <x-heroicon-o-adjustments-horizontal class="h-3.5 w-3.5 text-gray-400" />
                <select wire:model.live="sortBy" class="text-xs sm:text-sm bg-white border border-gray-200 rounded-lg px-3 py-1.5 text-gray-600 focus:outline-none focus:ring-1 focus:ring-gray-300 focus:border-gray-300 cursor-pointer">
                    <option value="default">เรียงตาม: ค่าเริ่มต้น</option>
                    <option value="price_asc">ราคาต่ำ → สูง</option>
                    <option value="price_desc">ราคาสูง → ต่ำ</option>
                    <option value="in_stock">สินค้าที่มีสต็อก</option>
                </select>
            </div>
        </div>

        <!-- Skeleton Loading -->
        <div wire:loading wire:target="setTab,sortBy">
            <x-skeleton type="product-grid" :count="8" />
        </div>

        <div wire:loading.remove wire:target="setTab,sortBy">
            <!-- Product Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($products->take($visibleCount) as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>

            @if($products->count() === 0)
                <div class="text-center py-16 text-gray-500">ไม่พบสินค้า</div>
            @endif

            <!-- Load More -->
            @if($visibleCount < $products->count())
                <div class="text-center mt-8">
                    <button wire:click="loadMore" class="px-8 py-2 rounded-full text-sm border border-gray-200 hover:bg-gray-50 transition-colors">Load More</button>
                </div>
            @endif
        </div>
    </section>
</div>
