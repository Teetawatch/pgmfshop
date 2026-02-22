@php
    $discountPercent = $product->original_price ? round(($product->original_price - $product->price) / $product->original_price * 100) : 0;
    $firstImage = is_array($product->images) ? ($product->images[0] ?? '/images/placeholder.png') : '/images/placeholder.png';
@endphp
<a href="{{ route('products.show', $product->slug) }}" class="group block h-full">
    <div class="h-full flex flex-col">

        <!-- Image -->
        <div class="relative overflow-hidden rounded-lg bg-gray-100" style="aspect-ratio: 1/1;">
            <img src="{{ $firstImage }}" alt="{{ $product->name }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 {{ $product->isOutOfStock() ? 'opacity-60 grayscale' : '' }}"
                loading="lazy" />

            <!-- Top-left type badge -->
            @if($product->product_type === 'clothing')
                <span class="absolute top-2.5 left-2.5 bg-purple-600 text-white text-[10px] font-bold px-2 py-0.5 rounded">เสื้อผ้า</span>
            @elseif($product->product_type === 'other')
                <span class="absolute top-2.5 left-2.5 bg-gray-700 text-white text-[10px] font-bold px-2 py-0.5 rounded">อื่นๆ</span>
            @endif

            <!-- Centered SOLD OUT overlay -->
            @if($product->isOutOfStock())
                <div class="absolute inset-0 flex items-center justify-center z-10">
                    <div class="bg-gray-900/90 text-white text-sm font-bold px-4 py-2 rounded-lg shadow-lg">
                        SOLD OUT
                    </div>
                </div>
            @endif

            <!-- Bottom-left badges -->
            <div class="absolute bottom-2.5 left-2.5 flex gap-1.5">
                @if($product->is_featured && !$product->isOutOfStock())
                    <span class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded">HOT</span>
                @endif
                @if($product->is_new && !$product->isOutOfStock())
                    <span class="bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded">NEW</span>
                @endif
                @if($discountPercent > 0 && !$product->isOutOfStock())
                    <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded">-{{ $discountPercent }}%</span>
                @endif
            </div>

            <!-- Wishlist -->
            <div class="absolute top-2.5 right-2.5 opacity-0 group-hover:opacity-100 transition-opacity duration-200" onclick="event.preventDefault(); event.stopPropagation();">
                <livewire:wishlist-button :product-id="$product->id" size="sm" :key="'wl-card-'.$product->id" />
            </div>
        </div>

        <!-- Info -->
        <div class="pt-3 flex-1 flex flex-col gap-1.5">
            <h3 class="text-sm text-gray-800 line-clamp-2 leading-snug group-hover:text-[hsl(var(--primary))] transition-colors duration-200">
                {{ $product->name }}
            </h3>
            <div class="mt-auto flex items-baseline gap-2">
                <span class="font-bold text-sm sm:text-base text-gray-900">฿ {{ number_format($product->price, 2) }}</span>
                @if($product->original_price)
                    <span class="text-xs text-gray-400 line-through">฿ {{ number_format($product->original_price, 2) }}</span>
                @endif
            </div>
        </div>

    </div>
</a>
