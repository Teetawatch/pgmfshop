<div class="min-h-screen bg-gray-50">

    {{-- Loading overlay --}}
    <div wire:loading.delay wire:target="addToCart,removeFromWishlist"
         class="fixed inset-0 bg-white/60 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="flex items-center gap-3 bg-white rounded-xl shadow-lg px-6 py-4 border">
            <svg class="h-5 w-5 animate-spin text-[#ff6b00]" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <span class="text-sm font-medium text-gray-600">กำลังดำเนินการ...</span>
        </div>
    </div>

    {{-- ===== HERO HEADER ===== --}}
    <div class="relative overflow-hidden"
         style="background: linear-gradient(135deg, #1f2937 0%, #374151 100%);">
        <div class="bg-[#FF6B00] absolute inset-0">
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-16">
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
                <a href="{{ route('account') }}" class="hover:text-white transition-colors">บัญชีของฉัน</a>
                <span class="material-icons-outlined text-xs">chevron_right</span>
                <span class="text-white font-medium">รายการโปรด</span>
            </div>
            {{-- Title --}}
            <div class="flex items-center gap-4">
                <div class="p-3 bg-white/10 rounded-xl">
                    <span class="material-icons-outlined text-3xl text-white">favorite</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">รายการโปรด</h1>
                    <p class="text-gray-400 text-sm mt-0.5 text-white">{{ $wishlists->count() }} รายการ</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT (overlaps hero) ===== --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 mb-16 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            {{-- ===== SIDEBAR ===== --}}
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-24">
                    <nav class="flex flex-col p-2 space-y-1">
                        <a href="{{ route('account') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <span class="material-icons-outlined">person</span>
                            ข้อมูลส่วนตัว
                        </a>
                        <a href="{{ route('account.orders') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <span class="material-icons-outlined">inventory_2</span>
                            คำสั่งซื้อ
                        </a>
                        <a href="{{ route('account.addresses') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <span class="material-icons-outlined">location_on</span>
                            ที่อยู่จัดส่ง
                        </a>
                        <a href="{{ route('account.wishlist') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg bg-orange-50 text-[#ff6b00] font-medium transition-colors">
                            <span class="material-icons-outlined">favorite</span>
                            รายการโปรด
                        </a>
                        @if(auth()->user()->role === 'admin')
                            <a href="/admin"
                               class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                <span class="material-icons-outlined">admin_panel_settings</span>
                                แอดมิน
                            </a>
                        @endif
                        <div class="my-1 border-t border-gray-100"></div>
                        <button wire:click="logout"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors w-full text-left">
                            <span class="material-icons-outlined">logout</span>
                            ออกจากระบบ
                        </button>
                    </nav>
                </div>
            </aside>

            {{-- ===== WISHLIST CONTENT ===== --}}
            <div class="lg:col-span-3">

                @if($wishlists->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach($wishlists as $wishlist)
                            @php
                                $product = $wishlist->product;
                                $firstImage = is_array($product->images) ? ($product->images[0] ?? '/images/placeholder.png') : '/images/placeholder.png';
                                $discountPercent = $product->original_price ? round(($product->original_price - $product->price) / $product->original_price * 100) : 0;
                                $isOutOfStock = $product->stock <= 0 || !$product->is_active;
                            @endphp
                            <div class="bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-md transition-shadow duration-200 flex flex-col group relative">

                                {{-- Product Image --}}
                                <a href="{{ route('products.show', $product->slug) }}" class="block">
                                    <div class="relative aspect-square overflow-hidden bg-gray-50">
                                        <img src="{{ $firstImage }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                             loading="lazy" />
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
                                                <span class="bg-[#ff6b00] text-white text-[10px] font-bold px-1.5 py-0.5 rounded">-{{ $discountPercent }}%</span>
                                            </div>
                                        @endif
                                        {{-- Remove button overlay --}}
                                        <button wire:click="removeFromWishlist({{ $product->id }})"
                                                wire:confirm="ต้องการนำออกจากรายการโปรด?"
                                                class="absolute top-2 right-2 w-8 h-8 rounded-full bg-white/90 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-white shadow-sm opacity-0 group-hover:opacity-100 transition-all duration-200
                                                    {{ $discountPercent > 0 ? 'hidden' : '' }}">
                                            <span class="material-icons-outlined text-lg">favorite</span>
                                        </button>
                                    </div>
                                </a>

                                {{-- Product Info --}}
                                <div class="p-4 flex-1 flex flex-col gap-2">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <h3 class="text-sm text-gray-800 font-medium line-clamp-2 leading-snug group-hover:text-[#ff6b00] transition-colors">
                                            {{ $product->name }}
                                        </h3>
                                    </a>
                                    @if($product->category)
                                        <p class="text-xs text-gray-400">{{ $product->category->name }}</p>
                                    @endif
                                    <div class="mt-auto pt-1">
                                        <div class="flex items-baseline gap-2">
                                            <span class="font-bold text-base text-gray-900">฿{{ number_format($product->price, 0) }}</span>
                                            @if($product->original_price)
                                                <span class="text-xs text-gray-400 line-through">฿{{ number_format($product->original_price, 0) }}</span>
                                            @endif
                                        </div>
                                        @if($product->review_count > 0)
                                            <div class="flex items-center gap-1 mt-1">
                                                <div class="flex">
                                                    @for($star = 1; $star <= 5; $star++)
                                                        <svg class="h-3 w-3 {{ $star <= round($product->rating) ? 'text-amber-400 fill-amber-400' : 'text-gray-200 fill-gray-200' }}" viewBox="0 0 24 24">
                                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="text-[10px] text-gray-400">({{ $product->review_count }})</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex gap-2 pt-3 border-t border-gray-100">
                                        @if(!$isOutOfStock)
                                            <button wire:click="addToCart({{ $product->id }})"
                                                    class="flex-1 flex items-center justify-center gap-1.5 py-2 px-3 bg-gray-900 text-white text-xs font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                                <span class="material-icons-outlined text-sm">shopping_cart</span>
                                                เพิ่มลงตะกร้า
                                            </button>
                                        @else
                                            <span class="flex-1 flex items-center justify-center py-2 px-3 bg-gray-100 text-gray-400 text-xs font-medium rounded-lg cursor-not-allowed">
                                                สินค้าหมด
                                            </span>
                                        @endif
                                        <button wire:click="removeFromWishlist({{ $product->id }})"
                                                wire:confirm="ต้องการนำออกจากรายการโปรด?"
                                                class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition-colors"
                                                title="นำออกจากรายการโปรด">
                                            <span class="material-icons-outlined text-lg">delete_outline</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                @else
                    {{-- Empty State --}}
                    <div class="bg-white rounded-xl shadow-sm p-12 flex flex-col items-center justify-center text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4 shadow-sm">
                            <span class="material-icons-outlined text-4xl text-gray-400">favorite_border</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">ยังไม่มีรายการโปรด</h2>
                        <p class="text-gray-500 text-sm max-w-xs mb-6">กดปุ่มหัวใจบนสินค้าที่สนใจเพื่อบันทึกไว้ดูภายหลัง</p>
                        <a href="{{ route('products') }}"
                           class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#ff6b00] text-white rounded-full text-sm font-medium hover:bg-orange-600 transition-colors shadow-sm">
                            <span class="material-icons-outlined text-base">shopping_bag</span>
                            เลือกซื้อสินค้า
                        </a>
                    </div>
                @endif

            </div>{{-- end lg:col-span-3 --}}
        </div>{{-- end grid --}}
    </main>
</div>
