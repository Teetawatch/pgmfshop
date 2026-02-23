@php
    $discountPercent = $product->original_price ? round(($product->original_price - $product->price) / $product->original_price * 100) : 0;
    $firstImage = is_array($product->images) ? ($product->images[0] ?? '/images/placeholder.png') : '/images/placeholder.png';
@endphp
<div class="group bg-white rounded-2xl border border-slate-100 hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden relative h-full flex flex-col">
    <!-- SOLD OUT overlay -->
    @if($product->isOutOfStock())
        <div class="absolute inset-0 z-20 bg-white/60 pointer-events-none flex items-center justify-center">
            <span class="bg-slate-800 text-white px-4 py-2 rounded font-bold tracking-wider text-sm shadow-xl">SOLD OUT</span>
        </div>
    @endif

    <!-- Top-left badges -->
    <div class="absolute top-3 left-3 z-10 flex flex-col gap-1.5">
        @if($product->is_featured && !$product->isOutOfStock())
            <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">HOT</span>
        @endif
        @if($product->is_new && !$product->isOutOfStock())
            <span class="bg-emerald-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">NEW</span>
        @endif
        @if($discountPercent > 0 && !$product->isOutOfStock())
            <span class="bg-[hsl(var(--primary))] text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">-{{ $discountPercent }}%</span>
        @endif
        @if($product->product_type === 'clothing')
            <span class="bg-purple-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">เสื้อผ้า</span>
        @elseif($product->product_type === 'other')
            <span class="bg-slate-700 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">อื่นๆ</span>
        @endif
    </div>

    <!-- Image -->
    <div class="relative aspect-4/5 overflow-hidden bg-gray-100 {{ $product->isOutOfStock() ? 'grayscale' : '' }}">
        <img src="{{ $firstImage }}" alt="{{ $product->name }}"
            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            loading="lazy" />
        <!-- Hover overlay with action buttons -->
        @if(!$product->isOutOfStock())
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3">
            <a href="{{ route('products.show', $product->slug) }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-slate-800 hover:text-[hsl(var(--primary))] hover:scale-110 transition-all shadow-lg">
                <x-heroicon-s-shopping-bag class="w-4 h-4" />
            </a>
            <div onclick="event.preventDefault(); event.stopPropagation();">
                <livewire:wishlist-button :product-id="$product->id" size="sm" :key="'wl-card-'.$product->id" />
            </div>
        </div>
        @endif
    </div>

    <!-- Info -->
    <div class="p-4 flex-1 flex flex-col">
        <h3 class="font-medium text-slate-800 line-clamp-2 min-h-[3rem] group-hover:text-[hsl(var(--primary))] transition-colors duration-200">
            {{ $product->name }}
        </h3>
        <div class="mt-4 flex items-end justify-between">
            <div class="flex flex-col">
                @if($product->original_price)
                    <span class="text-xs text-slate-400 line-through">฿ {{ number_format($product->original_price, 2) }}</span>
                @endif
                <span class="text-lg font-bold text-slate-900">฿ {{ number_format($product->price, 2) }}</span>
            </div>
            @if(!$product->isOutOfStock())
                <a href="{{ route('products.show', $product->slug) }}" class="text-[hsl(var(--primary))] text-sm font-medium hover:underline">ดูรายละเอียด</a>
            @endif
        </div>
    </div>
</div>
