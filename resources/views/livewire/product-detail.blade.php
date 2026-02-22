@php
    $images = is_array($product->images) ? $product->images : [];
    $discountPercent = $product->original_price ? round(($product->original_price - $product->price) / $product->original_price * 100) : 0;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Enhanced Breadcrumb -->
    <div class="mb-8">
        <nav class="flex items-center space-x-2 text-sm" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="flex items-center text-gray-500 hover:text-[hsl(var(--primary))] transition-colors duration-200">
                <x-heroicon-o-home class="w-4 h-4 mr-1" />
                หน้าแรก
            </a>
            
            <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
            
            <a href="{{ route('products') }}" class="text-gray-500 hover:text-[hsl(var(--primary))] transition-colors duration-200">
                สินค้า
            </a>
            
            <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
            
            <a href="{{ route('products', ['category' => $product->category->slug]) }}" class="text-gray-500 hover:text-[hsl(var(--primary))] transition-colors duration-200">
                {{ $product->category->name }}
            </a>
            
            <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-400" />
            
            <span class="text-gray-900 font-medium">{{ $product->name }}</span>
        </nav>
    </div>

    <!-- JSON-LD Product Structured Data -->
    @php
        $jsonLd = [
            "\x40context" => 'https://schema.org',
            "\x40type" => 'Product',
            'name' => $product->name,
            'description' => mb_substr(strip_tags($product->description ?? ''), 0, 300),
            'image' => !empty($images) ? $images : [],
            'sku' => $product->sku ?: (string) $product->id,
            'category' => $product->product_type_label,
            'brand' => [
                "\x40type" => 'Organization',
                'name' => 'PGMF Shop',
            ],
            'offers' => [
                "\x40type" => 'Offer',
                'url' => url()->current(),
                'priceCurrency' => 'THB',
                'price' => number_format((float) $product->price, 2, '.', ''),
                'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => [
                    "\x40type" => 'Organization',
                    'name' => 'PGMF Shop',
                ],
            ],
        ];
        if ($product->review_count > 0) {
            $jsonLd['aggregateRating'] = [
                "\x40type" => 'AggregateRating',
                'ratingValue' => number_format((float) $product->rating, 1, '.', ''),
                'reviewCount' => $product->review_count,
            ];
        }
    @endphp
    @push('seo')
    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
    @endpush

    <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
        <!-- Images -->
        <div class="space-y-4">
            <div class="relative aspect-square rounded-lg overflow-hidden bg-gray-100">
                @if(count($images) > 0)
                    <div x-data="{ 
                        currentImage: @entangle('selectedImage'),
                        isTransitioning: false
                    }" x-init="$watch('currentImage', (newIndex, oldIndex) => {
                        if (newIndex !== oldIndex) {
                            isTransitioning = true;
                            const container = $el.querySelector('.image-slider');
                            container.style.transform = `translateX(-${newIndex * 100}%)`;
                            setTimeout(() => { isTransitioning = false; }, 200);
                        }
                    })" class="w-full h-full overflow-hidden">
                        <div class="image-slider flex h-full" :class="isTransitioning ? 'transition-transform duration-200 ease-out' : 'transition-none'" :style="`transform: translateX(-${currentImage * 100}%)`">
                            @foreach($images as $img)
                                <div class="w-full h-full shrink-0">
                                    <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if($discountPercent > 0)
                    <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded">-{{ $discountPercent }}%</span>
                @endif
            </div>
            @if(count($images) > 1)
                <div class="flex gap-2 overflow-x-auto">
                    @foreach($images as $idx => $img)
                        <button wire:click="selectImage({{ $idx }})" 
                                class="relative w-20 h-20 rounded-md overflow-hidden border-2 shrink-0 transition-all duration-150 hover:scale-105 {{ $selectedImage === $idx ? 'border-[hsl(var(--primary))] shadow-lg' : 'border-transparent hover:border-gray-300' }}">
                            <img src="{{ $img }}" alt="{{ $product->name }} {{ $idx + 1 }}" class="w-full h-full object-cover transition-transform duration-150 hover:scale-110" />
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="space-y-5">
            <!-- Category & Type Badge -->
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <a href="{{ route('products', ['category' => $product->category->slug]) }}" class="text-sm text-gray-500 hover:text-[hsl(var(--primary))] transition-colors">{{ $product->category->name }}</a>
                    @if($product->product_type !== 'book')
                        @php
                            $typeBadgeClass = match($product->product_type) {
                                'clothing' => 'bg-purple-100 text-purple-700',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $typeBadgeClass }}">{{ $product->product_type_label }}</span>
                    @endif
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>
            </div>

            <!-- Rating & Sales -->
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-1">
                    @for($star = 1; $star <= 5; $star++)
                        <svg class="h-4 w-4 {{ $star <= round($product->rating) ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200' }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @endfor
                    <span class="ml-1 text-sm font-semibold text-gray-700">{{ $product->rating }}</span>
                </div>
                <span class="text-gray-300">|</span>
                <span class="text-sm text-gray-500">{{ $product->review_count }} รีวิว</span>
                <span class="text-gray-300">|</span>
                <span class="text-sm text-gray-500">ขายแล้ว {{ number_format($product->sold) }} ชิ้น</span>
            </div>

            <!-- Price -->
            @php
                $hasVariants = $product->isClothing() && $product->variants->isNotEmpty();
            @endphp
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl font-bold text-[hsl(var(--primary))]">฿{{ number_format($product->price, 0) }}</span>
                    @if($product->original_price)
                        <span class="text-lg text-gray-400 line-through">฿{{ number_format($product->original_price, 0) }}</span>
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">-{{ $discountPercent }}%</span>
                    @endif
                </div>
                @if($hasVariants)
                    @if($selectedSize || $selectedColor)
                        @if($currentVariantStock > 0)
                            <p class="text-xs text-green-600 mt-1.5 flex items-center gap-1">
                                <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                                มีสินค้า (คงเหลือ {{ $currentVariantStock }} ชิ้น)
                            </p>
                        @else
                            <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                                <x-heroicon-s-x-circle class="w-3.5 h-3.5" />
                                ตัวเลือกนี้สินค้าหมด
                            </p>
                        @endif
                    @else
                        <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                            <x-heroicon-s-information-circle class="w-3.5 h-3.5" />
                            กรุณาเลือกไซส์/สี เพื่อดูจำนวนสินค้าคงเหลือ
                        </p>
                    @endif
                @elseif($product->stock > 0)
                    <p class="text-xs text-green-600 mt-1.5 flex items-center gap-1">
                        <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                        มีสินค้า (คงเหลือ {{ $product->stock }} ชิ้น)
                    </p>
                @else
                    <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                        <x-heroicon-s-x-circle class="w-3.5 h-3.5" />
                        สินค้าหมด
                    </p>
                @endif
            </div>

            <!-- Book Info -->
            @if($product->product_type === 'book' && ($product->publisher || !empty($product->authors) || !empty($product->genres) || $product->pages))
            <div class="rounded-xl border border-blue-100 bg-blue-50/40 p-4">
                <h3 class="text-sm font-semibold text-blue-800 flex items-center gap-1.5 mb-3">
                    <x-heroicon-o-book-open class="w-4 h-4" />
                    ข้อมูลหนังสือ
                </h3>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                    @if($product->publisher)
                        <div><span class="text-gray-400">สำนักพิมพ์</span></div>
                        <div class="font-medium text-gray-700">{{ $product->publisher }}</div>
                    @endif
                    @if(!empty($product->authors))
                        <div><span class="text-gray-400">ผู้แต่ง</span></div>
                        <div class="font-medium text-gray-700">{{ implode(', ', $product->authors) }}</div>
                    @endif
                    @if(!empty($product->genres))
                        <div><span class="text-gray-400">หมวดหมู่</span></div>
                        <div class="font-medium text-gray-700">{{ implode(', ', $product->genres) }}</div>
                    @endif
                    @if($product->pages)
                        <div><span class="text-gray-400">จำนวนหน้า</span></div>
                        <div class="font-medium text-gray-700">{{ number_format($product->pages) }} หน้า</div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Clothing: Size Selector -->
            @if($product->isClothing() && !empty($product->sizes))
            <div>
                <div class="flex items-center justify-between mb-2.5">
                    <h3 class="text-sm font-semibold text-gray-800">ขนาด / ไซส์</h3>
                    @if($selectedSize)
                        <span class="text-xs text-[hsl(var(--primary))] font-medium">เลือกแล้ว: {{ $selectedSize }}</span>
                    @else
                        <span class="text-xs text-red-500">* กรุณาเลือก</span>
                    @endif
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($product->sizes as $size)
                        @php
                            // Calculate total stock for this size across all colors
                            $sizeStock = 0;
                            if ($hasVariants) {
                                $sizeStock = $product->variants->where('size', $size)->where('is_active', true)->sum('stock');
                            }
                            $sizeOutOfStock = $hasVariants && $sizeStock <= 0;
                        @endphp
                        <button
                            wire:click="$set('selectedSize', '{{ $size }}')"
                            @if($sizeOutOfStock) disabled @endif
                            class="relative min-w-12 px-4 py-2.5 rounded-lg border-2 text-sm font-medium transition-all duration-200
                                {{ $sizeOutOfStock
                                    ? 'border-gray-100 text-gray-300 bg-gray-50 cursor-not-allowed line-through'
                                    : ($selectedSize === $size
                                        ? 'border-[hsl(var(--primary))] bg-[hsl(var(--primary))]/5 text-[hsl(var(--primary))] shadow-sm ring-1 ring-[hsl(var(--primary))]/20'
                                        : 'border-gray-200 text-gray-700 hover:border-gray-400 hover:bg-gray-50') }}"
                        >
                            {{ $size }}
                            @if($hasVariants && !$sizeOutOfStock)
                                <span class="block text-[10px] font-normal {{ $selectedSize === $size ? 'text-[hsl(var(--primary))]/70' : 'text-gray-400' }}">เหลือ {{ $sizeStock }}</span>
                            @endif
                            @if($sizeOutOfStock)
                                <span class="block text-[10px] font-normal text-gray-300">หมด</span>
                            @endif
                            @if($selectedSize === $size && !$sizeOutOfStock)
                                <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-[hsl(var(--primary))] rounded-full flex items-center justify-center">
                                    <x-heroicon-o-check class="w-2.5 h-2.5 text-white" />
                                </span>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Clothing: Color Selector -->
            @if($product->isClothing() && !empty($product->colors))
            <div>
                <div class="flex items-center justify-between mb-2.5">
                    <h3 class="text-sm font-semibold text-gray-800">สี / ลาย</h3>
                    @if($selectedColor)
                        <span class="text-xs text-[hsl(var(--primary))] font-medium">เลือกแล้ว: {{ $selectedColor }}</span>
                    @else
                        <span class="text-xs text-red-500">* กรุณาเลือก</span>
                    @endif
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($product->colors as $color)
                        @php
                            // Calculate stock for this color (filtered by selected size if any)
                            $colorStock = 0;
                            if ($hasVariants) {
                                $colorVariants = $product->variants->where('color', $color)->where('is_active', true);
                                if ($selectedSize) {
                                    $colorVariants = $colorVariants->where('size', $selectedSize);
                                }
                                $colorStock = $colorVariants->sum('stock');
                            }
                            $colorOutOfStock = $hasVariants && $colorStock <= 0;
                        @endphp
                        <button
                            wire:click="$set('selectedColor', '{{ $color }}')"
                            @if($colorOutOfStock) disabled @endif
                            class="relative px-4 py-2.5 rounded-lg border-2 text-sm font-medium transition-all duration-200
                                {{ $colorOutOfStock
                                    ? 'border-gray-100 text-gray-300 bg-gray-50 cursor-not-allowed line-through'
                                    : ($selectedColor === $color
                                        ? 'border-[hsl(var(--primary))] bg-[hsl(var(--primary))]/5 text-[hsl(var(--primary))] shadow-sm ring-1 ring-[hsl(var(--primary))]/20'
                                        : 'border-gray-200 text-gray-700 hover:border-gray-400 hover:bg-gray-50') }}"
                        >
                            {{ $color }}
                            @if($hasVariants && !$colorOutOfStock)
                                <span class="block text-[10px] font-normal {{ $selectedColor === $color ? 'text-[hsl(var(--primary))]/70' : 'text-gray-400' }}">เหลือ {{ $colorStock }}</span>
                            @endif
                            @if($colorOutOfStock)
                                <span class="block text-[10px] font-normal text-gray-300">หมด</span>
                            @endif
                            @if($selectedColor === $color && !$colorOutOfStock)
                                <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-[hsl(var(--primary))] rounded-full flex items-center justify-center">
                                    <x-heroicon-o-check class="w-2.5 h-2.5 text-white" />
                                </span>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Clothing: Material -->
            @if($product->isClothing() && $product->material)
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <x-heroicon-o-sparkles class="w-4 h-4 text-gray-400" />
                <span class="text-gray-400">เนื้อผ้า:</span>
                <span class="font-medium">{{ $product->material }}</span>
            </div>
            @endif

            <!-- Description -->
            <div>
                <h3 class="text-sm font-semibold text-gray-800 mb-2">รายละเอียดสินค้า</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $product->description }}</p>
            </div>

            <!-- SKU & Weight -->
            @if($product->weight || $product->sku)
            <div class="flex flex-wrap gap-x-6 gap-y-1 text-xs text-gray-400">
                @if($product->sku)
                    <span>SKU: {{ $product->sku }}</span>
                @endif
                @if($product->weight)
                    <span>น้ำหนัก: {{ number_format($product->weight) }} กรัม</span>
                @endif
            </div>
            @endif

            <hr class="border-gray-100">

            <!-- Quantity -->
            <div class="flex items-center gap-4">
                <span class="text-sm font-semibold text-gray-800">จำนวน</span>
                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                    <button wire:click="decrementQty" class="h-10 w-10 flex items-center justify-center hover:bg-gray-50 active:bg-gray-100 transition-colors {{ $quantity <= 1 ? 'opacity-40 cursor-not-allowed' : '' }}">
                        <x-heroicon-o-minus class="h-4 w-4" />
                    </button>
                    <span class="w-12 text-center font-semibold text-gray-800 border-x border-gray-200">{{ $quantity }}</span>
                    <button wire:click="incrementQty" class="h-10 w-10 flex items-center justify-center hover:bg-gray-50 active:bg-gray-100 transition-colors {{ $quantity >= $currentVariantStock ? 'opacity-40 cursor-not-allowed' : '' }}">
                        <x-heroicon-o-plus class="h-4 w-4" />
                    </button>
                </div>
            </div>

            <!-- Actions -->
            @php $canAddToCart = $currentVariantStock > 0; @endphp
            <div class="flex gap-3">
                <button wire:click="addToCart" wire:loading.attr="disabled" wire:target="addToCart" class="flex-1 flex items-center justify-center gap-2 py-3.5 bg-[hsl(var(--primary))] text-white rounded-xl font-semibold hover:opacity-90 active:scale-[0.98] transition-all {{ !$canAddToCart ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$canAddToCart ? 'disabled' : '' }}>
                    <x-heroicon-o-shopping-cart wire:loading.remove wire:target="addToCart" class="h-5 w-5" />
                    <svg wire:loading wire:target="addToCart" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span wire:loading.remove wire:target="addToCart">เพิ่มลงตะกร้า</span>
                    <span wire:loading wire:target="addToCart">กำลังเพิ่ม...</span>
                </button>
                <button wire:click="buyNow" wire:loading.attr="disabled" wire:target="buyNow" class="flex-1 py-3.5 border-2 border-[hsl(var(--primary))] text-[hsl(var(--primary))] rounded-xl font-semibold hover:bg-[hsl(var(--primary))]/5 active:scale-[0.98] transition-all {{ !$canAddToCart ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$canAddToCart ? 'disabled' : '' }}>
                    <span wire:loading.remove wire:target="buyNow">ซื้อเลย</span>
                    <span wire:loading wire:target="buyNow" class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        กำลังดำเนินการ...
                    </span>
                </button>
                <livewire:wishlist-button :product-id="$product->id" size="md" :key="'wl-detail-'.$product->id" />
            </div>

            <!-- Features -->
            <div class="grid grid-cols-3 gap-3">
                <div class="flex flex-col items-center gap-1.5 py-3 px-2 bg-gray-50 rounded-xl">
                    <x-heroicon-o-truck class="h-5 w-5 text-[hsl(var(--primary))]" />
                    <span class="text-xs font-medium text-gray-700">จัดส่ง 1-3 วัน</span>
                </div>
                <div class="flex flex-col items-center gap-1.5 py-3 px-2 bg-gray-50 rounded-xl">
                    <x-heroicon-o-shield-check class="h-5 w-5 text-[hsl(var(--primary))]" />
                    <span class="text-xs font-medium text-gray-700">สินค้าแท้ 100%</span>
                </div>
                <div class="flex flex-col items-center gap-1.5 py-3 px-2 bg-gray-50 rounded-xl">
                    <x-heroicon-o-arrow-uturn-left class="h-5 w-5 text-[hsl(var(--primary))]" />
                    <span class="text-xs font-medium text-gray-700">คืนใน 7 วัน</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <section class="mt-16">
        <h2 class="text-2xl font-bold mb-6">รีวิวจากลูกค้า ({{ $product->review_count }})</h2>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Rating Summary -->
            <div class="bg-gray-50 rounded-xl p-6 text-center">
                <p class="text-5xl font-bold text-gray-800">{{ number_format($product->rating, 1) }}</p>
                <div class="flex justify-center gap-0.5 mt-2">
                    @for($star = 1; $star <= 5; $star++)
                        <svg class="h-5 w-5 {{ $star <= round($product->rating) ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300' }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @endfor
                </div>
                <p class="text-sm text-gray-500 mt-1">จาก {{ $product->review_count }} รีวิว</p>

                @php
                    $reviews = $product->reviews;
                    $ratingCounts = [];
                    for ($i = 5; $i >= 1; $i--) {
                        $ratingCounts[$i] = $reviews->where('rating', $i)->count();
                    }
                @endphp
                <div class="mt-4 space-y-1.5 text-left">
                    @for($i = 5; $i >= 1; $i--)
                        <div class="flex items-center gap-2 text-xs">
                            <span class="w-3 text-gray-500">{{ $i }}</span>
                            <svg class="h-3 w-3 text-yellow-400 fill-yellow-400 shrink-0" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-400 rounded-full" style="width: {{ $product->review_count > 0 ? ($ratingCounts[$i] / $product->review_count * 100) : 0 }}%"></div>
                            </div>
                            <span class="w-6 text-right text-gray-400">{{ $ratingCounts[$i] }}</span>
                        </div>
                    @endfor
                </div>

                <!-- Write Review Button -->
                @auth
                    @if($canReview)
                        <button wire:click="toggleReviewForm" class="mt-5 w-full py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            เขียนรีวิว
                        </button>
                    @elseif($hasReviewed)
                        <p class="mt-5 text-xs text-gray-400">คุณรีวิวสินค้านี้แล้ว</p>
                    @else
                        <p class="mt-5 text-xs text-gray-400">ซื้อสินค้านี้แล้วจึงจะรีวิวได้</p>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="mt-5 block w-full py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors text-center">
                        เข้าสู่ระบบเพื่อรีวิว
                    </a>
                @endauth
            </div>

            <!-- Reviews List -->
            <div class="md:col-span-2">
                <!-- Review Form -->
                @if($showReviewForm)
                    <div class="border border-teal-200 bg-teal-50/30 rounded-xl p-5 mb-6">
                        <h3 class="font-semibold text-gray-800 mb-4">เขียนรีวิวของคุณ</h3>
                        <form wire:submit="submitReview" class="space-y-4">
                            <!-- Star Rating -->
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">ให้คะแนน</label>
                                <div class="flex gap-1" x-data>
                                    @for($star = 1; $star <= 5; $star++)
                                        <button type="button" wire:click="setRating({{ $star }})" class="focus:outline-none transition-transform hover:scale-110">
                                            <svg class="h-8 w-8 cursor-pointer {{ $star <= $reviewRating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300 hover:text-yellow-300' }} transition-colors" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        </button>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-500 self-center">
                                        @php
                                            $ratingLabels = [1 => 'แย่มาก', 2 => 'แย่', 3 => 'ปานกลาง', 4 => 'ดี', 5 => 'ดีมาก'];
                                        @endphp
                                        {{ $ratingLabels[$reviewRating] ?? '' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Comment -->
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">รีวิว</label>
                                <textarea wire:model="reviewComment" rows="4" placeholder="แชร์ประสบการณ์ของคุณเกี่ยวกับสินค้านี้..." class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-300 transition resize-none"></textarea>
                                @error('reviewComment') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex gap-3">
                                <button type="submit" class="px-6 py-2.5 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors">
                                    ส่งรีวิว
                                </button>
                                <button type="button" wire:click="toggleReviewForm" class="px-6 py-2.5 border border-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                    ยกเลิก
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                @if($product->reviews->count() > 0)
                    <div class="space-y-5">
                        @foreach($product->reviews->sortByDesc('created_at') as $review)
                            <div class="border rounded-xl p-5 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        @if($review->user && ($review->user->social_avatar || $review->user->avatar))
                                            <img src="{{ $review->user->social_avatar ?: $review->user->avatar }}" alt="" class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100" referrerpolicy="no-referrer" />
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-600">
                                                {{ strtoupper(mb_substr($review->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-sm">{{ $review->user->name ?? 'ผู้ใช้' }}</p>
                                            <p class="text-xs text-gray-400">{{ $review->created_at->locale('th')->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-0.5">
                                        @for($star = 1; $star <= 5; $star++)
                                            <svg class="h-4 w-4 {{ $star <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200' }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <x-heroicon-o-chat-bubble-left-right class="h-12 w-12 mx-auto text-gray-300 mb-3" />
                        <p class="text-gray-400 text-sm">ยังไม่มีรีวิว เป็นคนแรกที่รีวิวสินค้านี้!</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if(count($relatedProducts) > 0)
        <section class="mt-16">
            <h2 class="text-2xl font-bold mb-6">สินค้าที่เกี่ยวข้อง</h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($relatedProducts as $rp)
                    @include('partials.product-card', ['product' => $rp])
                @endforeach
            </div>
        </section>
    @endif
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('stock-exceeded', () => {
        Swal.fire({
            icon: 'warning',
            title: 'มีข้อผิดพลาด',
            text: 'จำนวนที่คุณเลือกเกินกว่าสต็อกที่มีอยู่ กรุณาเลือกจำนวนที่น้อยลง',
            confirmButtonColor: 'hsl(var(--primary))',
            confirmButtonText: 'ตกลง'
        });
    });
});
</script>
