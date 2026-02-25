@php
    $images = is_array($product->images) ? $product->images : [];
    $discountPercent = $product->original_price ? round(($product->original_price - $product->price) / $product->original_price * 100) : 0;
    $hasVariants = $product->isClothing() && $product->variants->isNotEmpty();
@endphp

@php
    $jsonLd = [
        "\x40context" => 'https://schema.org',
        "\x40type" => 'Product',
        'name' => $product->name,
        'description' => mb_substr(strip_tags($product->description ?? ''), 0, 300),
        'image' => !empty($images) ? $images : [],
        'sku' => $product->sku ?: (string) $product->id,
        'category' => $product->product_type_label,
        'brand' => ["\x40type" => 'Organization', 'name' => 'PGMF Shop'],
        'offers' => [
            "\x40type" => 'Offer',
            'url' => url()->current(),
            'priceCurrency' => 'THB',
            'price' => number_format((float) $product->price, 2, '.', ''),
            'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'seller' => ["\x40type" => 'Organization', 'name' => 'PGMF Shop'],
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

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-8 flex items-center gap-2 overflow-x-auto whitespace-nowrap">
        <a href="{{ route('home') }}" class="hover:text-[hsl(var(--primary))] transition-colors flex items-center gap-1">
            <x-heroicon-o-home class="w-4 h-4" />
            หน้าแรก
        </a>
        <x-heroicon-o-chevron-right class="w-3.5 h-3.5 text-gray-400 shrink-0" />
        <a href="{{ route('products') }}" class="hover:text-[hsl(var(--primary))] transition-colors">สินค้า</a>
        <x-heroicon-o-chevron-right class="w-3.5 h-3.5 text-gray-400 shrink-0" />
        <a href="{{ route('products', ['category' => $product->category->slug]) }}" class="hover:text-[hsl(var(--primary))] transition-colors">{{ $product->category->name }}</a>
        <x-heroicon-o-chevron-right class="w-3.5 h-3.5 text-gray-400 shrink-0" />
        <span class="text-gray-900 font-medium truncate">{{ $product->name }}</span>
    </nav>

    {{-- Main 12-col Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16">

        {{-- LEFT: Images + Description (desktop) --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- Main Image --}}
            <div class="relative aspect-4/5 w-full rounded-2xl overflow-hidden bg-gray-100 shadow-lg group"
                 x-data="{ currentImage: @entangle('selectedImage') }">
                @if(count($images) > 0)
                    <div class="w-full h-full overflow-hidden">
                        <div class="image-slider flex transition-transform duration-500 ease-in-out h-full"
                             :style="`transform: translateX(-${currentImage * 100}%)`">
                            @foreach($images as $img)
                                <div class="w-full h-full shrink-0">
                                    <img src="{{ $img }}" alt="{{ $product->name }}"
                                         class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <x-heroicon-o-photo class="w-24 h-24" />
                    </div>
                @endif

                @if($discountPercent > 0)
                    <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow">-{{ $discountPercent }}%</span>
                @endif

                <div class="absolute bottom-4 right-4 bg-white/80 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-medium text-gray-600 flex items-center gap-1 pointer-events-none">
                    Zoom <x-heroicon-o-magnifying-glass-plus class="w-4 h-4" />
                </div>
            </div>

            {{-- Thumbnail Strip --}}
            @if(count($images) > 1)
                <div class="flex gap-3 overflow-x-auto no-scrollbar pb-1">
                    @foreach($images as $idx => $img)
                        <button wire:click="selectImage({{ $idx }})"
                                class="shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 transition-all duration-200
                                    {{ $selectedImage === $idx ? 'border-[hsl(var(--primary))] shadow-md' : 'border-transparent hover:border-gray-300' }}">
                            <img src="{{ $img }}" alt="{{ $product->name }} {{ $idx + 1 }}" class="w-full h-full object-cover" />
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Description — desktop only --}}
            <div class="hidden lg:block pt-8 border-t border-gray-200">
                <h3 class="text-xl font-bold mb-4">รายละเอียดสินค้า</h3>
                <div class="prose max-w-none text-gray-600">
                    <p class="leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
                </div>
                @if($product->weight || $product->sku)
                    <div class="flex flex-wrap gap-x-6 gap-y-1 text-xs text-gray-400 mt-4">
                        @if($product->sku)<span>SKU: {{ $product->sku }}</span>@endif
                        @if($product->weight)<span>น้ำหนัก: {{ number_format($product->weight) }} กรัม</span>@endif
                    </div>
                @endif
            </div>

        </div>
        {{-- END LEFT --}}

        {{-- RIGHT: Sticky Product Info --}}
        <div class="lg:col-span-5">
            <div class="sticky top-24 space-y-6">

                {{-- Badges + Title + Rating --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <a href="{{ route('products', ['category' => $product->category->slug]) }}"
                           class="bg-gray-100 text-gray-600 px-2.5 py-0.5 rounded text-xs font-semibold uppercase tracking-wide hover:bg-gray-200 transition-colors">
                            {{ $product->category->name }}
                        </a>
                        @if($product->product_type !== 'book')
                            @php
                                $typeBadgeClass = match($product->product_type) {
                                    'clothing' => 'bg-purple-100 text-purple-600',
                                    default => 'bg-gray-100 text-gray-500',
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 rounded text-xs font-semibold uppercase tracking-wide {{ $typeBadgeClass }}">{{ $product->product_type_label }}</span>
                        @endif
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">{{ $product->name }}</h1>
                    <div class="flex items-center gap-3 flex-wrap text-sm text-gray-500">
                        <div class="flex items-center gap-0.5">
                            @for($star = 1; $star <= 5; $star++)
                                <svg class="h-4 w-4 {{ $star <= round($product->rating) ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200 fill-gray-200' }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            @endfor
                        </div>
                        <span>{{ number_format($product->rating, 1) }} ({{ $product->review_count }} รีวิว)</span>
                        <span class="w-1 h-1 bg-gray-300 rounded-full inline-block"></span>
                        <span>ขายแล้ว {{ number_format($product->sold) }} ชิ้น</span>
                    </div>
                </div>

                {{-- Price Panel --}}
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                    <div class="flex items-baseline gap-2 mb-2">
                        <span class="text-3xl font-bold text-[hsl(var(--primary))]">฿{{ number_format($product->price, 0) }}</span>
                        @if($product->original_price)
                            <span class="text-lg text-gray-400 line-through">฿{{ number_format($product->original_price, 0) }}</span>
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">-{{ $discountPercent }}%</span>
                        @endif
                    </div>
                    <div class="flex items-start gap-2 text-xs">
                        @if($hasVariants)
                            @if($selectedSize || $selectedColor)
                                @if($currentVariantStock > 0)
                                    <x-heroicon-s-check-circle class="w-4 h-4 text-green-500 mt-0.5 shrink-0" />
                                    <p class="text-green-600">มีสินค้า (คงเหลือ {{ $currentVariantStock }} ชิ้น)</p>
                                @else
                                    <x-heroicon-s-x-circle class="w-4 h-4 text-red-400 mt-0.5 shrink-0" />
                                    <p class="text-red-500">ตัวเลือกนี้สินค้าหมด</p>
                                @endif
                            @else
                                <x-heroicon-o-information-circle class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" />
                                <p class="text-gray-500">กรุณาเลือกไซส์/สี เพื่อดูจำนวนสินค้าคงเหลือ</p>
                            @endif
                        @elseif($product->stock > 0)
                            <x-heroicon-s-check-circle class="w-4 h-4 text-green-500 mt-0.5 shrink-0" />
                            <p class="text-green-600">มีสินค้า (คงเหลือ {{ $product->stock }} ชิ้น)</p>
                        @else
                            <x-heroicon-s-x-circle class="w-4 h-4 text-red-400 mt-0.5 shrink-0" />
                            <p class="text-red-500">สินค้าหมด</p>
                        @endif
                    </div>
                </div>

                {{-- Book Info --}}
                @if($product->product_type === 'book' && ($product->publisher || !empty($product->authors) || !empty($product->genres) || $product->pages))
                <div class="rounded-xl border border-blue-100 bg-blue-50/40 p-4">
                    <h3 class="text-sm font-semibold text-blue-800 flex items-center gap-1.5 mb-3">
                        <x-heroicon-o-book-open class="w-4 h-4" />
                        ข้อมูลหนังสือ
                    </h3>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        @if($product->publisher)
                            <span class="text-gray-400">สำนักพิมพ์</span>
                            <span class="font-medium text-gray-700">{{ $product->publisher }}</span>
                        @endif
                        @if(!empty($product->authors))
                            <span class="text-gray-400">ผู้แต่ง</span>
                            <span class="font-medium text-gray-700">{{ implode(', ', $product->authors) }}</span>
                        @endif
                        @if(!empty($product->genres))
                            <span class="text-gray-400">หมวดหมู่</span>
                            <span class="font-medium text-gray-700">{{ implode(', ', $product->genres) }}</span>
                        @endif
                        @if($product->pages)
                            <span class="text-gray-400">จำนวนหน้า</span>
                            <span class="font-medium text-gray-700">{{ number_format($product->pages) }} หน้า</span>
                        @endif
                    </div>
                </div>
                @endif
                {{-- END BOOK INFO --}}

                {{-- Variants --}}
                <div class="space-y-5">

                    {{-- Size Selector --}}
                    @if($product->isClothing() && !empty($product->sizes))
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-medium text-gray-900">ขนาด / ไซส์</span>
                            @if($selectedSize)
                                <span class="text-xs text-[hsl(var(--primary))] font-medium">เลือกแล้ว: {{ $selectedSize }}</span>
                            @else
                                <span class="text-xs text-red-500 font-medium">* กรุณาเลือก</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @foreach($product->sizes as $size)
                                @php
                                    $sizeStock = 0;
                                    if ($hasVariants) {
                                        $sizeStock = $product->variants->where('size', $size)->where('is_active', true)->sum('stock');
                                    }
                                    $sizeOutOfStock = $hasVariants && $sizeStock <= 0;
                                @endphp
                                <button
                                    wire:click="$set('selectedSize', '{{ $size }}')"
                                    @if($sizeOutOfStock) disabled @endif
                                    class="relative w-16 h-12 rounded-lg border-2 text-sm font-medium flex flex-col items-center justify-center transition-all duration-200
                                        {{ $sizeOutOfStock
                                            ? 'border-gray-100 text-gray-300 bg-gray-50 cursor-not-allowed opacity-50'
                                            : ($selectedSize === $size
                                                ? 'border-[hsl(var(--primary))] bg-[hsl(var(--primary))]/5 text-[hsl(var(--primary))] shadow-sm'
                                                : 'border-gray-200 text-gray-900 hover:border-[hsl(var(--primary))] hover:text-[hsl(var(--primary))]') }}"
                                >
                                    <span class="{{ $sizeOutOfStock ? 'line-through' : '' }}">{{ $size }}</span>
                                    @if($hasVariants)
                                        <span class="text-[10px] font-normal opacity-80 {{ $sizeOutOfStock ? '' : ($selectedSize === $size ? '' : 'text-gray-500') }}">
                                            {{ $sizeOutOfStock ? 'หมด' : 'เหลือ '.$sizeStock }}
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Color Selector --}}
                    @if($product->isClothing() && !empty($product->colors))
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-medium text-gray-900">สี / ลาย</span>
                            @if($selectedColor)
                                <span class="text-xs text-[hsl(var(--primary))] font-medium">เลือกแล้ว: {{ $selectedColor }}</span>
                            @else
                                <span class="text-xs text-red-500 font-medium">* กรุณาเลือก</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @foreach($product->colors as $color)
                                @php
                                    $colorStock = 0;
                                    if ($hasVariants) {
                                        $colorVariants = $product->variants->where('color', $color)->where('is_active', true);
                                        if ($selectedSize) {
                                            $colorVariants = $colorVariants->where('size', $selectedSize);
                                        }
                                        $colorStock = $colorVariants->sum('stock');
                                    }
                                    $colorOutOfStock = $hasVariants && $colorStock <= 0;
                                    $colorMap = [
                                        'ดำ' => '#111827', 'ขาว' => '#F9FAFB', 'แดง' => '#EF4444',
                                        'น้ำเงิน' => '#3B82F6', 'เขียว' => '#22C55E', 'เหลือง' => '#EAB308',
                                        'ส้ม' => '#F97316', 'ชมพู' => '#EC4899', 'เทา' => '#9CA3AF',
                                        'น้ำตาล' => '#92400E', 'ม่วง' => '#A855F7',
                                    ];
                                    $dotColor = $colorMap[$color] ?? '#9CA3AF';
                                    $isLight = in_array($color, ['ขาว', 'เหลือง']);
                                @endphp
                                <button
                                    wire:click="$set('selectedColor', '{{ $color }}')"
                                    @if($colorOutOfStock) disabled @endif
                                    class="group relative px-4 py-2 rounded-lg border-2 flex items-center gap-2 transition-all duration-200 bg-white
                                        {{ $colorOutOfStock
                                            ? 'border-gray-100 opacity-50 cursor-not-allowed'
                                            : ($selectedColor === $color
                                                ? 'border-[hsl(var(--primary))] shadow-sm'
                                                : 'border-gray-200 hover:border-gray-300') }}"
                                >
                                    <span class="w-4 h-4 rounded-full shrink-0 {{ $isLight ? 'border border-gray-200' : '' }}"
                                          style="background-color: {{ $dotColor }}"></span>
                                    <div class="flex flex-col text-left">
                                        <span class="text-sm font-medium text-gray-900 {{ $colorOutOfStock ? 'line-through text-gray-400' : 'group-hover:text-[hsl(var(--primary))]' }}">{{ $color }}</span>
                                        @if($hasVariants)
                                            <span class="text-[10px] text-gray-500">{{ $colorOutOfStock ? 'หมด' : 'เหลือ '.$colorStock }}</span>
                                        @endif
                                    </div>
                                    @if($selectedColor === $color && !$colorOutOfStock)
                                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[hsl(var(--primary))] opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-[hsl(var(--primary))]"></span>
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Material --}}
                    @if($product->isClothing() && $product->material)
                    <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <x-heroicon-o-sparkles class="w-4 h-4 text-[hsl(var(--primary))] shrink-0" />
                        <span>เนื้อผ้า: <strong>{{ $product->material }}</strong></span>
                    </div>
                    @endif

                </div>
                {{-- END VARIANTS --}}

                {{-- Quantity & Actions --}}
                <div class="pt-5 border-t border-gray-200 space-y-4">

                    {{-- Quantity Row --}}
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-900">จำนวน</span>
                        <div class="flex items-center border border-gray-200 rounded-lg bg-white">
                            <button wire:click="decrementQty"
                                    class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-[hsl(var(--primary))] transition-colors {{ $quantity <= 1 ? 'opacity-40 cursor-not-allowed' : '' }}">
                                <x-heroicon-o-minus class="w-4 h-4" />
                            </button>
                            <span class="w-12 h-10 flex items-center justify-center text-center font-semibold text-gray-900 border-x border-gray-200">{{ $quantity }}</span>
                            <button wire:click="incrementQty"
                                    class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-[hsl(var(--primary))] transition-colors {{ $quantity >= $currentVariantStock ? 'opacity-40 cursor-not-allowed' : '' }}">
                                <x-heroicon-o-plus class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    @php $canAddToCart = $currentVariantStock > 0; @endphp
                    <div class="grid grid-cols-12 gap-3">
                        <button wire:click="addToCart"
                                wire:loading.attr="disabled" wire:target="addToCart"
                                @if(!$canAddToCart) disabled @endif
                                class="col-span-12 md:col-span-5 bg-[hsl(var(--primary))] hover:bg-[hsl(var(--primary))]/90 text-white font-semibold py-3.5 px-4 rounded-xl shadow-lg shadow-orange-500/20 transition-all active:scale-95 flex items-center justify-center gap-2 {{ !$canAddToCart ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <x-heroicon-o-shopping-cart wire:loading.remove wire:target="addToCart" class="h-5 w-5" />
                            <svg wire:loading wire:target="addToCart" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            <span wire:loading.remove wire:target="addToCart">เพิ่มลงตะกร้า</span>
                            <span wire:loading wire:target="addToCart">กำลังเพิ่ม...</span>
                        </button>

                        <button wire:click="buyNow"
                                wire:loading.attr="disabled" wire:target="buyNow"
                                @if(!$canAddToCart) disabled @endif
                                class="col-span-10 md:col-span-5 border-2 border-gray-200 hover:border-[hsl(var(--primary))] hover:text-[hsl(var(--primary))] text-gray-700 font-semibold py-3.5 px-4 rounded-xl transition-all active:scale-95 bg-white {{ !$canAddToCart ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <span wire:loading.remove wire:target="buyNow">ซื้อเลย</span>
                            <span wire:loading wire:target="buyNow" class="inline-flex items-center justify-center gap-2">
                                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                กำลังดำเนินการ...
                            </span>
                        </button>

                        <div class="col-span-2 flex items-center justify-center">
                            <livewire:wishlist-button :product-id="$product->id" size="md" :key="'wl-detail-'.$product->id" />
                        </div>
                    </div>

                </div>

                {{-- Feature Badges --}}
                <div class="grid grid-cols-3 gap-2 py-1">
                    <div class="flex flex-col items-center justify-center p-3 rounded-lg bg-gray-50 text-center gap-2">
                        <x-heroicon-o-truck class="h-6 w-6 text-[hsl(var(--primary))]" />
                        <span class="text-[10px] md:text-xs font-medium text-gray-600">จัดส่ง 1-3 วัน</span>
                    </div>
                    <div class="flex flex-col items-center justify-center p-3 rounded-lg bg-gray-50 text-center gap-2">
                        <x-heroicon-o-shield-check class="h-6 w-6 text-[hsl(var(--primary))]" />
                        <span class="text-[10px] md:text-xs font-medium text-gray-600">สินค้าแท้ 100%</span>
                    </div>
                    <div class="flex flex-col items-center justify-center p-3 rounded-lg bg-gray-50 text-center gap-2">
                        <x-heroicon-o-arrow-uturn-left class="h-6 w-6 text-[hsl(var(--primary))]" />
                        <span class="text-[10px] md:text-xs font-medium text-gray-600">คืนใน 7 วัน</span>
                    </div>
                </div>

                {{-- Description — mobile only --}}
                <div class="block lg:hidden pt-4 border-t border-gray-200"
                     x-data="{ expanded: false }">
                    <h3 class="font-bold mb-2">รายละเอียดสินค้า</h3>
                    <p class="text-sm text-gray-600 leading-relaxed"
                       :class="expanded ? '' : 'line-clamp-4'">{{ $product->description }}</p>
                    <button @click="expanded = !expanded"
                            class="text-[hsl(var(--primary))] text-xs font-medium mt-1"
                            x-text="expanded ? 'ย่อลง' : 'อ่านเพิ่มเติม'"></button>
                    @if($product->weight || $product->sku)
                        <div class="flex flex-wrap gap-x-6 gap-y-1 text-xs text-gray-400 mt-3">
                            @if($product->sku)<span>SKU: {{ $product->sku }}</span>@endif
                            @if($product->weight)<span>น้ำหนัก: {{ number_format($product->weight) }} กรัม</span>@endif
                        </div>
                    @endif
                </div>

            </div>
        </div>
        {{-- END RIGHT --}}

    </div>
    {{-- END MAIN GRID --}}

    {{-- Reviews Section --}}
    <section class="mt-20 border-t border-gray-200 pt-12">
        <h2 class="text-2xl font-bold mb-8">รีวิวจากลูกค้า ({{ $product->review_count }})</h2>

        <div class="grid md:grid-cols-12 gap-8">

            {{-- Rating Summary Panel --}}
            <div class="md:col-span-4 lg:col-span-3">
                <div class="bg-gray-50 rounded-2xl p-6 text-center border border-gray-100">
                    <div class="text-6xl font-bold text-gray-900 mb-2">{{ number_format($product->rating, 1) }}</div>
                    <div class="flex justify-center gap-0.5 mb-2">
                        @for($star = 1; $star <= 5; $star++)
                            <svg class="h-5 w-5 {{ $star <= round($product->rating) ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300 fill-gray-300' }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-500 mb-6">จาก {{ $product->review_count }} รีวิว</p>

                    @php
                        $reviews = $product->reviews;
                        $ratingCounts = [];
                        for ($i = 5; $i >= 1; $i--) {
                            $ratingCounts[$i] = $reviews->where('rating', $i)->count();
                        }
                    @endphp
                    <div class="space-y-2 text-xs text-gray-500">
                        @for($i = 5; $i >= 1; $i--)
                            <div class="flex items-center gap-2">
                                <span class="w-3 text-right">{{ $i }}</span>
                                <svg class="h-2.5 w-2.5 text-yellow-400 fill-yellow-400 shrink-0" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-yellow-400 rounded-full" style="width: {{ $product->review_count > 0 ? ($ratingCounts[$i] / $product->review_count * 100) : 0 }}%"></div>
                                </div>
                                <span class="w-3 text-right">{{ $ratingCounts[$i] }}</span>
                            </div>
                        @endfor
                    </div>

                    @auth
                        @if($canReview)
                            <button wire:click="toggleReviewForm"
                                    class="w-full mt-6 bg-gray-800 hover:bg-gray-900 text-white py-2.5 rounded-lg text-sm font-medium transition-colors">
                                เขียนรีวิว
                            </button>
                        @elseif($hasReviewed)
                            <p class="mt-6 text-xs text-gray-400">คุณรีวิวสินค้านี้แล้ว</p>
                        @else
                            <p class="mt-6 text-xs text-gray-400">ซื้อสินค้านี้แล้วจึงจะรีวิวได้</p>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="mt-6 block w-full py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors text-center">
                            เข้าสู่ระบบเพื่อรีวิว
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Reviews Content --}}
            <div class="md:col-span-8 lg:col-span-9">

                {{-- Review Form --}}
                @if($showReviewForm)
                    <div class="border border-teal-200 bg-teal-50/30 rounded-2xl p-5 mb-6">
                        <h3 class="font-semibold text-gray-800 mb-4">เขียนรีวิวของคุณ</h3>
                        <form wire:submit="submitReview" class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">ให้คะแนน</label>
                                <div class="flex gap-1" x-data>
                                    @for($star = 1; $star <= 5; $star++)
                                        <button type="button" wire:click="setRating({{ $star }})"
                                                class="focus:outline-none transition-transform hover:scale-110">
                                            <svg class="h-8 w-8 cursor-pointer {{ $star <= $reviewRating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300 hover:text-yellow-300' }} transition-colors" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        </button>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-500 self-center">
                                        @php $ratingLabels = [1 => 'แย่มาก', 2 => 'แย่', 3 => 'ปานกลาง', 4 => 'ดี', 5 => 'ดีมาก']; @endphp
                                        {{ $ratingLabels[(int)$reviewRating] ?? '' }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">รีวิว</label>
                                <textarea wire:model="reviewComment" rows="4"
                                          placeholder="แชร์ประสบการณ์ของคุณเกี่ยวกับสินค้านี้..."
                                          class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-300 transition resize-none"></textarea>
                                @error('reviewComment') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex gap-3">
                                <button type="submit"
                                        class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    ส่งรีวิว
                                </button>
                                <button type="button" wire:click="toggleReviewForm"
                                        class="px-6 py-2.5 border border-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                    ยกเลิก
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- Reviews List or Empty State --}}
                @if($reviews->count() > 0)
                    <div class="space-y-5">
                        @foreach($reviews->sortByDesc('created_at') as $review)
                            <div class="border border-gray-100 rounded-2xl p-5 space-y-3 bg-white">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        @if($review->user && ($review->user->social_avatar || $review->user->avatar))
                                            <img src="{{ $review->user->social_avatar ?: $review->user->avatar }}"
                                                 alt="" class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100"
                                                 referrerpolicy="no-referrer" />
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
                                            <svg class="h-4 w-4 {{ $star <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200 fill-gray-200' }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center min-h-[300px] text-gray-400 border border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                        <x-heroicon-o-chat-bubble-left-right class="h-16 w-16 mb-4 opacity-40" />
                        <p class="text-lg">ยังไม่มีรีวิว เป็นคนแรกที่รีวิวสินค้านี้!</p>
                    </div>
                @endif

            </div>
        </div>
    </section>

    {{-- Related Products --}}
    @if(count($relatedProducts) > 0)
        <section class="mt-16 border-t border-gray-200 pt-12">
            <h2 class="text-2xl font-bold mb-6">สินค้าที่เกี่ยวข้อง</h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($relatedProducts as $rp)
                    @include('partials.product-card', ['product' => $rp])
                @endforeach
            </div>
        </section>
    @endif

@push('styles')
<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush
@push('scripts')
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
@endpush
</div>