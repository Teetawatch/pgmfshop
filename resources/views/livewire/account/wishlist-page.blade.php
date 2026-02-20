<div class="min-h-screen bg-gray-50">
    {{-- Loading overlay for wishlist actions --}}
    <div wire:loading.delay wire:target="addToCart,removeFromWishlist" class="fixed inset-0 bg-white/60 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="flex items-center gap-3 bg-white rounded-xl shadow-lg px-6 py-4 border">
            <svg class="h-5 w-5 animate-spin text-[#FF6512]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            <span class="text-sm font-medium text-gray-600">กำลังดำเนินการ...</span>
        </div>
    </div>

    <!-- Hero Header -->
    <div class="bg-[#FF6512]">
        <div class="container mx-auto px-4 py-8">
            <div class="flex items-center gap-2 text-sm text-white/70 mb-4">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">หน้าแรก</a>
                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('account') }}" class="hover:text-white transition-colors">บัญชีของฉัน</a>
                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                <span class="text-white font-medium">รายการโปรด</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">รายการโปรด</h1>
                    <p class="text-white/70 text-sm">{{ $wishlists->count() }} รายการ</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">

    @if($wishlists->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($wishlists as $wishlist)
                @php
                    $product = $wishlist->product;
                    $firstImage = is_array($product->images) ? ($product->images[0] ?? '/images/placeholder.png') : '/images/placeholder.png';
                    $discountPercent = $product->original_price ? round(($product->original_price - $product->price) / $product->original_price * 100) : 0;
                    $isOutOfStock = $product->stock <= 0 || !$product->is_active;
                @endphp
                <div class="bg-white rounded-lg overflow-hidden border border-gray-100 hover:shadow-md transition-shadow duration-200 flex flex-col group relative">
                    {{-- Image --}}
                    <a href="{{ route('products.show', $product->slug) }}" class="block">
                        <div class="relative aspect-square overflow-hidden bg-gray-50">
                            <img src="{{ $firstImage }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                            @if($isOutOfStock)
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                    <span class="bg-white/90 text-gray-700 text-xs font-bold px-3 py-1.5 rounded-full">สินค้าหมด</span>
                                </div>
                            @endif
                            {{-- Badges --}}
                            <div class="absolute top-2 left-2 flex gap-1">
                                @if($product->is_featured)
                                    <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">HOT</span>
                                @endif
                                @if($product->is_new)
                                    <span class="bg-emerald-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">NEW</span>
                                @endif
                            </div>
                            @if($discountPercent > 0)
                                <div class="absolute top-2 right-2">
                                    <span class="bg-orange-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">-{{ $discountPercent }}%</span>
                                </div>
                            @endif
                        </div>
                    </a>

                    {{-- Info --}}
                    <div class="p-3 flex-1 flex flex-col gap-1.5">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <h3 class="text-xs sm:text-sm text-gray-700 line-clamp-2 leading-snug group-hover:text-[hsl(var(--primary))] transition-colors">
                                {{ $product->name }}
                            </h3>
                        </a>
                        @if($product->category)
                            <p class="text-[11px] text-gray-400">{{ $product->category->name }}</p>
                        @endif
                        <div class="mt-auto">
                            <div class="flex items-baseline gap-2">
                                <span class="font-bold text-sm sm:text-base text-gray-900">฿{{ number_format($product->price, 0) }}</span>
                                @if($product->original_price)
                                    <span class="text-[11px] text-gray-400 line-through">฿{{ number_format($product->original_price, 0) }}</span>
                                @endif
                            </div>
                            @if($product->review_count > 0)
                                <div class="flex items-center gap-1 mt-1">
                                    <div class="flex">
                                        @for($star = 1; $star <= 5; $star++)
                                            <svg class="h-2.5 w-2.5 {{ $star <= round($product->rating) ? 'text-amber-400 fill-amber-400' : 'text-gray-200 fill-gray-200' }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        @endfor
                                    </div>
                                    <span class="text-[10px] text-gray-400">{{ $product->review_count }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2 mt-2 pt-2 border-t border-gray-100">
                            @if(!$isOutOfStock)
                                <button wire:click="addToCart({{ $product->id }})" class="flex-1 flex items-center justify-center gap-1.5 py-2 px-3 bg-gray-800 text-white text-xs font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 002 1.58h9.78a2 2 0 001.95-1.57l1.65-7.43H5.12"/></svg>
                                    เพิ่มลงตะกร้า
                                </button>
                            @else
                                <span class="flex-1 flex items-center justify-center py-2 px-3 bg-gray-100 text-gray-400 text-xs font-medium rounded-lg cursor-not-allowed">
                                    สินค้าหมด
                                </span>
                            @endif
                            <button wire:click="removeFromWishlist({{ $product->id }})" wire:confirm="ต้องการนำออกจากรายการโปรด?" class="flex items-center justify-center h-9 w-9 rounded-lg border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition-colors" title="นำออกจากรายการโปรด">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-16">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <svg class="h-10 w-10 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">ยังไม่มีรายการโปรด</h3>
            <p class="text-sm text-gray-500 mb-6">กดปุ่มหัวใจบนสินค้าที่สนใจเพื่อบันทึกไว้ดูภายหลัง</p>
            <a href="{{ route('products') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                เลือกซื้อสินค้า
            </a>
        </div>
    @endif
    </div>
</div>
