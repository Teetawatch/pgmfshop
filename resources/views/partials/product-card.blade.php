@php
    $discountPercent = $product->original_price ? round(($product->original_price - $product->price) / $product->original_price * 100) : 0;
    $firstImage = is_array($product->images) ? ($product->images[0] ?? '/images/placeholder.png') : '/images/placeholder.png';
    $outOfStock = $product->isOutOfStock();
@endphp
<div class="group bg-white rounded-xl border border-slate-100 overflow-hidden hover:shadow-2xl hover:shadow-orange-500/5 transition-all duration-300 h-full flex flex-col">

    {{-- Image --}}
    <div class="relative aspect-4/5 overflow-hidden">
        <img src="{{ $firstImage }}" alt="{{ $product->name }}"
             class="w-full h-full object-cover transition-transform duration-500 {{ $outOfStock ? 'grayscale opacity-60' : 'group-hover:scale-110' }}"
             loading="lazy" />

        {{-- Sold out overlay --}}
        @if($outOfStock)
            <div class="absolute inset-0 bg-black/20 flex items-center justify-center backdrop-blur-[2px]">
                <span class="px-6 py-2 bg-black/80 text-white text-sm font-bold rounded-full border border-white/20 tracking-wider">SOLD OUT</span>
            </div>
        @endif

        {{-- Top-left badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1.5">
            @if($product->is_featured && !$outOfStock)
                <span class="px-2.5 py-1 bg-red-500 text-white text-[10px] font-bold rounded-full uppercase tracking-wider shadow-sm">Hot</span>
            @endif
            @if($product->is_new && !$outOfStock)
                <span class="px-2.5 py-1 bg-blue-500 text-white text-[10px] font-bold rounded-full uppercase tracking-wider shadow-sm">New</span>
            @endif
            @if($discountPercent > 0 && !$outOfStock)
                <span class="px-2.5 py-1 bg-orange-500 text-white text-[10px] font-bold rounded-full uppercase tracking-wider shadow-sm">-{{ $discountPercent }}%</span>
            @endif
            @if($product->product_type === 'clothing')
                <span class="px-2.5 py-1 bg-purple-600 text-white text-[10px] font-bold rounded-full uppercase tracking-wider shadow-sm">เสื้อผ้า</span>
            @elseif($product->product_type === 'other')
                <span class="px-2.5 py-1 bg-slate-700 text-white text-[10px] font-bold rounded-full uppercase tracking-wider shadow-sm">อื่นๆ</span>
            @endif
        </div>

        {{-- Wishlist button (top-right, appears on hover) --}}
        @if(!$outOfStock)
            <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300"
                 onclick="event.preventDefault(); event.stopPropagation();">
                <livewire:wishlist-button :product-id="$product->id" size="sm" :key="'wl-card-'.$product->id" />
            </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="p-4 flex-1 flex flex-col">
        {{-- Rating --}}
        @if($product->rating > 0)
            <div class="flex items-center gap-1 mb-2">
                <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <span class="text-[10px] font-bold text-slate-400">{{ number_format($product->rating, 1) }}
                    @if($product->reviews_count ?? 0)
                        ({{ $product->reviews_count }} รีวิว)
                    @endif
                </span>
            </div>
        @else
            <div class="flex items-center gap-1 mb-2">
                <svg class="w-3.5 h-3.5 text-slate-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <span class="text-[10px] font-bold text-slate-400">ยังไม่มีรีวิว</span>
            </div>
        @endif

        <h3 class="font-bold text-slate-800 line-clamp-2 leading-snug flex-1 group-hover:text-orange-500 transition-colors duration-200">
            {{ $product->name }}
        </h3>

        <div class="mt-4 flex items-end justify-between">
            <div class="flex flex-col">
                @if($product->original_price)
                    <span class="text-xs text-slate-400 line-through">฿{{ number_format($product->original_price, 2) }}</span>
                @endif
                <span class="text-xl font-bold {{ $outOfStock ? 'text-slate-500' : 'text-orange-500' }}">
                    ฿{{ number_format($product->price, 2) }}
                </span>
            </div>
            @if(!$outOfStock)
                <a href="{{ route('products.show', $product->slug) }}"
                   class="p-2.5 bg-slate-900 text-white rounded-lg hover:bg-orange-500 transition-colors shadow-sm">
                    <x-heroicon-o-shopping-cart class="w-5 h-5" />
                </a>
            @else
                <button disabled class="p-2.5 bg-slate-200 text-slate-400 rounded-lg cursor-not-allowed">
                    <x-heroicon-o-shopping-cart class="w-5 h-5" />
                </button>
            @endif
        </div>
    </div>
</div>
