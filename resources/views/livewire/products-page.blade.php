<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Breadcrumb --}}
        @php
            $breadcrumbItems = [
                ['label' => 'หน้าแรก', 'url' => route('home')],
                ['label' => 'สินค้า', 'url' => $currentCategory ? route('products') : ''],
            ];
            if ($currentCategory) {
                $breadcrumbItems[] = ['label' => $currentCategory->name];
            }
        @endphp
        <x-breadcrumb :items="$breadcrumbItems" />

        <div class="flex flex-col lg:flex-row gap-8 mt-6">

            {{-- ─── Sidebar ─── --}}
            <aside class="{{ $showFilters ? 'block' : 'hidden' }} lg:block w-full lg:w-72 shrink-0 space-y-6">

                {{-- Search --}}
                <div class="relative">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="ค้นหาสินค้า..."
                           class="w-full pl-10 pr-10 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-white" />
                    @if($search)
                        <button wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <x-heroicon-o-x-mark class="h-4 w-4" />
                        </button>
                    @endif
                </div>

                {{-- Product Type --}}
                <div>
                    <h3 class="text-base font-bold mb-4 flex items-center gap-2 text-slate-800">
                        <x-heroicon-o-squares-2x2 class="h-5 w-5 text-orange-500" />
                        ประเภทสินค้า
                    </h3>
                    <ul class="space-y-1">
                        <li>
                            <button wire:click="$set('type', '')"
                                    class="w-full flex justify-between items-center px-4 py-2.5 rounded-xl font-medium text-sm transition-all
                                           {{ !$type ? 'bg-orange-500 text-white shadow-md shadow-orange-200' : 'text-slate-600 hover:bg-slate-100' }}">
                                <span>ทุกประเภท</span>
                                <span class="{{ !$type ? 'opacity-70' : 'text-slate-400' }} text-xs">{{ $total }}</span>
                            </button>
                        </li>
                        @foreach(\App\Models\Product::PRODUCT_TYPES as $typeKey => $typeLabel)
                            <li>
                                <button wire:click="$set('type', '{{ $typeKey }}')"
                                        class="w-full flex justify-between items-center px-4 py-2.5 rounded-xl font-medium text-sm transition-all
                                               {{ $type === $typeKey ? 'bg-orange-500 text-white shadow-md shadow-orange-200' : 'text-slate-600 hover:bg-slate-100' }}">
                                    <span>{{ $typeLabel }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Categories --}}
                <div>
                    <h3 class="text-base font-bold mb-4 flex items-center gap-2 text-slate-800">
                        <x-heroicon-o-tag class="h-5 w-5 text-orange-500" />
                        หมวดหมู่
                    </h3>
                    <ul class="space-y-1">
                        <li>
                            <button wire:click="$set('category', '')"
                                    class="w-full flex justify-between items-center px-4 py-2.5 rounded-xl font-medium text-sm transition-all
                                           {{ !$category ? 'bg-orange-500 text-white shadow-md shadow-orange-200' : 'text-slate-600 hover:bg-slate-100' }}">
                                <span>ทั้งหมด</span>
                            </button>
                        </li>
                        @foreach($categories as $cat)
                            <li>
                                <button wire:click="$set('category', '{{ $cat->slug }}')"
                                        class="w-full flex justify-between items-center px-4 py-2.5 rounded-xl font-medium text-sm transition-all
                                               {{ $category === $cat->slug ? 'bg-orange-500 text-white shadow-md shadow-orange-200' : 'text-slate-600 hover:bg-slate-100' }}">
                                    <span>{{ $cat->name }}</span>
                                    <span class="{{ $category === $cat->slug ? 'opacity-70' : 'text-slate-400' }} text-xs">{{ $cat->products_count }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Active filter chips --}}
                @if($category || $search || $type)
                    <div class="p-4 bg-orange-50 border border-orange-100 rounded-xl">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold text-orange-700 uppercase tracking-wider">ตัวกรองที่ใช้</span>
                            <button wire:click="clearFilters" class="text-xs text-orange-600 hover:underline font-medium">ล้างทั้งหมด</button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if($type)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-white text-orange-700 border border-orange-200 shadow-sm">
                                    {{ \App\Models\Product::PRODUCT_TYPES[$type] ?? $type }}
                                    <button wire:click="$set('type', '')"><x-heroicon-o-x-mark class="h-3 w-3" /></button>
                                </span>
                            @endif
                            @if($category && $currentCategory)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-white text-orange-700 border border-orange-200 shadow-sm">
                                    {{ $currentCategory->name }}
                                    <button wire:click="$set('category', '')"><x-heroicon-o-x-mark class="h-3 w-3" /></button>
                                </span>
                            @endif
                            @if($search)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-white text-orange-700 border border-orange-200 shadow-sm">
                                    "{{ $search }}"
                                    <button wire:click="$set('search', '')"><x-heroicon-o-x-mark class="h-3 w-3" /></button>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

            </aside>

            {{-- ─── Main Content ─── --}}
            <div class="flex-1 min-w-0">

                {{-- Header row --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 mb-1">
                            {{ $currentCategory ? $currentCategory->name : 'สินค้าทั้งหมด' }}
                        </h1>
                        <p class="text-slate-500 text-sm">พบสินค้าทั้งหมด {{ $total }} รายการ</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-slate-500 hidden sm:block">เรียงตาม:</span>
                        <select wire:model.live="sort"
                                class="bg-white border border-slate-200 rounded-full text-sm py-2 pl-4 pr-10 focus:ring-orange-400 focus:border-orange-400 min-w-[160px]">
                            <option value="default">แนะนำ</option>
                            <option value="price-asc">ราคา: ต่ำ-สูง</option>
                            <option value="price-desc">ราคา: สูง-ต่ำ</option>
                            <option value="rating">คะแนนสูงสุด</option>
                            <option value="bestselling">ขายดี</option>
                            <option value="newest">สินค้าใหม่</option>
                        </select>
                        {{-- Mobile filter toggle --}}
                        <button wire:click="$toggle('showFilters')"
                                class="lg:hidden inline-flex items-center justify-center h-10 w-10 border border-slate-200 rounded-full hover:bg-slate-50 transition-colors">
                            <x-heroicon-o-adjustments-vertical class="h-4 w-4 text-slate-600" />
                        </button>
                    </div>
                </div>

                {{-- Loading skeleton --}}
                <div wire:loading wire:target="search,sort,category,type">
                    <x-skeleton type="product-grid" :count="6" cols="grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6" />
                </div>

                {{-- Products grid --}}
                <div wire:loading.remove wire:target="search,sort,category,type">
                    @if($products->count() > 0)
                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                            @foreach($products as $product)
                                @include('partials.product-card', ['product' => $product])
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-24">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <x-heroicon-o-magnifying-glass class="h-7 w-7 text-slate-400" />
                            </div>
                            <p class="text-slate-500 text-lg font-medium">ไม่พบสินค้าที่ค้นหา</p>
                            <p class="text-slate-400 text-sm mt-1 mb-4">ลองเปลี่ยนคำค้นหาหรือตัวกรอง</p>
                            <button wire:click="clearFilters"
                                    class="px-5 py-2 bg-orange-500 text-white rounded-full text-sm font-medium hover:bg-orange-600 transition-colors">
                                ล้างตัวกรอง
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
