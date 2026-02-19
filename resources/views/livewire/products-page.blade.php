<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
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

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">
            {{ $currentCategory ? $currentCategory->name : 'สินค้าทั้งหมด' }}
        </h1>
        <p class="text-gray-500">
            {{ $currentCategory ? $currentCategory->description : "พบสินค้า {$total} รายการ" }}
        </p>
    </div>

    <!-- Search & Filters -->
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="ค้นหาสินค้า..." class="w-full pl-10 pr-10 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))]" />
            @if($search)
                <button wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            @endif
        </div>
        <div class="flex gap-2">
            <select wire:model.live="sort" class="h-10 rounded-md border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))]">
                <option value="default">เรียงตาม: แนะนำ</option>
                <option value="price-asc">ราคา: ต่ำ → สูง</option>
                <option value="price-desc">ราคา: สูง → ต่ำ</option>
                <option value="rating">คะแนนสูงสุด</option>
                <option value="bestselling">ขายดี</option>
                <option value="newest">ใหม่ล่าสุด</option>
            </select>
            <button wire:click="$toggle('showFilters')" class="md:hidden inline-flex items-center justify-center h-10 w-10 border border-gray-200 rounded-md hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="4" x2="20" y1="21" y2="21"/><line x1="4" x2="20" y1="14" y2="14"/><line x1="4" x2="20" y1="7" y2="7"/><circle cx="8" cy="21" r="1"/><circle cx="16" cy="14" r="1"/><circle cx="8" cy="7" r="1"/></svg>
            </button>
        </div>
    </div>

    <div class="flex gap-8">
        <!-- Sidebar Filters -->
        <aside class="{{ $showFilters ? 'block' : 'hidden' }} md:block w-full md:w-56 shrink-0">
            <div class="space-y-6">
                <div class="space-y-2">
                    <h3 class="font-semibold">ประเภทสินค้า</h3>
                    <div class="space-y-2">
                        <button wire:click="$set('type', '')" class="block text-sm w-full text-left py-1 px-2 rounded {{ !$type ? 'bg-[hsl(var(--primary))]/10 text-[hsl(var(--primary))] font-medium' : 'hover:bg-gray-100' }}">
                            ทุกประเภท
                        </button>
                        @foreach(\App\Models\Product::PRODUCT_TYPES as $typeKey => $typeLabel)
                            <button wire:click="$set('type', '{{ $typeKey }}')" class="block text-sm w-full text-left py-1 px-2 rounded {{ $type === $typeKey ? 'bg-[hsl(var(--primary))]/10 text-[hsl(var(--primary))] font-medium' : 'hover:bg-gray-100' }}">
                                {{ $typeLabel }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-2">
                    <h3 class="font-semibold">หมวดหมู่</h3>
                    <div class="space-y-2">
                        <button wire:click="$set('category', '')" class="block text-sm w-full text-left py-1 px-2 rounded {{ !$category ? 'bg-[hsl(var(--primary))]/10 text-[hsl(var(--primary))] font-medium' : 'hover:bg-gray-100' }}">
                            ทั้งหมด
                        </button>
                        @foreach($categories as $cat)
                            <button wire:click="$set('category', '{{ $cat->slug }}')" class="block text-sm w-full text-left py-1 px-2 rounded {{ $category === $cat->slug ? 'bg-[hsl(var(--primary))]/10 text-[hsl(var(--primary))] font-medium' : 'hover:bg-gray-100' }}">
                                {{ $cat->name }} ({{ $cat->products_count }})
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>

        <!-- Products Grid -->
        <div class="flex-1">
            <!-- Active Filters -->
            @if($category || $search || $type)
                <div class="flex flex-wrap gap-2 mb-4">
                    @if($type)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                            ประเภท: {{ \App\Models\Product::PRODUCT_TYPES[$type] ?? $type }}
                            <button wire:click="$set('type', '')"><svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
                        </span>
                    @endif
                    @if($category && $currentCategory)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                            {{ $currentCategory->name }}
                            <button wire:click="$set('category', '')"><svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
                        </span>
                    @endif
                    @if($search)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                            ค้นหา: {{ $search }}
                            <button wire:click="$set('search', '')"><svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
                        </span>
                    @endif
                </div>
            @endif

            <div wire:loading wire:target="search,sort,category,type">
                <x-skeleton type="product-grid" :count="6" cols="grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6" />
            </div>

            <div wire:loading.remove wire:target="search,sort,category,type">
                @if($products->count() > 0)
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                        @foreach($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <p class="text-gray-500 text-lg">ไม่พบสินค้าที่ค้นหา</p>
                        <button wire:click="clearFilters" class="text-[hsl(var(--primary))] hover:underline mt-2 text-sm">ล้างตัวกรอง</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
