<div class="min-h-screen bg-slate-50">
    {{-- Loading overlay for cart actions --}}
    <div wire:loading.delay wire:target="updateQuantity,removeItem,clearCart,applyCoupon" class="fixed inset-0 bg-white/60 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="flex items-center gap-3 bg-white rounded-2xl shadow-lg px-6 py-4 border border-slate-100">
            <svg class="h-5 w-5 animate-spin text-[#FF6B00]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            <span class="text-sm font-medium text-slate-600">กำลังอัปเดตตะกร้า...</span>
        </div>
    </div>

    <!-- Hero Header -->
    <section class="bg-[#FF6B00] pt-10 pb-20 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <x-heroicon-o-shopping-cart class="h-6 w-6 text-white" />
                </div>
                <div>
                    <h1 class="text-3xl font-bold">ตะกร้าสินค้า</h1>
                    <p class="text-white/80 font-light">สินค้าที่คุณเลือกไว้ทั้งหมด</p>
                </div>
            </div>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 pb-20">
    @if(count($items) === 0)
        {{-- Empty State --}}
        <div class="max-w-2xl mx-auto py-12">
            <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-slate-100">
                <div class="relative inline-block mb-8">
                    <div class="w-32 h-32 bg-[#FF6B00]/5 rounded-full flex items-center justify-center">
                        <x-heroicon-o-shopping-bag class="h-16 w-16 text-[#FF6B00]/40" />
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg border-2 border-slate-50">
                        <x-heroicon-o-plus-circle class="h-6 w-6 text-[#FF6B00]" />
                    </div>
                </div>
                <h2 class="text-2xl font-bold mb-3 text-slate-800">ตะกร้าว่างเปล่า</h2>
                <p class="text-slate-500 mb-8 max-w-sm mx-auto">
                    ดูเหมือนว่าคุณยังไม่ได้เลือกสินค้าชิ้นใดเลย <br/>เริ่มค้นหาสินค้าที่ถูกใจได้แล้ววันนี้!
                </p>
                <a href="{{ route('products') }}" class="inline-flex items-center gap-2 bg-[#FF6B00] hover:bg-orange-600 text-white font-bold px-10 py-4 rounded-xl shadow-lg shadow-[#FF6B00]/20 transition-all transform hover:-translate-y-1">
                    ไปที่หน้าร้านค้า
                    <x-heroicon-o-arrow-right class="h-5 w-5" />
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($items as $item)
                    @php
                        $product = $item['product'];
                        $qty = $item['quantity'];
                        $cartKey = $item['cart_key'];
                        $options = $item['options'] ?? [];
                        $maxStock = $item['max_stock'] ?? $product->stock;
                        $firstImage = is_array($product->images) ? ($product->images[0] ?? '') : '';
                    @endphp
                    <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-6 group">
                        {{-- Product Image --}}
                        <div class="w-full md:w-32 h-32 bg-slate-100 rounded-xl overflow-hidden shrink-0">
                            @if($firstImage)
                                <img src="{{ $firstImage }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <x-heroicon-o-photo class="h-10 w-10 text-slate-300" />
                                </div>
                            @endif
                        </div>

                        {{-- Product Info --}}
                        <div class="flex-1 flex flex-col justify-between">
                            <div class="flex justify-between items-start">
                                <div class="flex-1 min-w-0 pr-3">
                                    <a href="{{ route('products.show', $product->slug) }}" class="text-lg font-semibold text-slate-800 hover:text-[#FF6B00] transition-colors line-clamp-2">{{ $product->name }}</a>
                                    @if(!empty($options['size']) || !empty($options['color']))
                                        <p class="text-sm text-slate-500 mt-1">
                                            @if(!empty($options['size']))ไซส์: {{ $options['size'] }}@endif
                                            @if(!empty($options['size']) && !empty($options['color'])) | @endif
                                            @if(!empty($options['color']))สี: {{ $options['color'] }}@endif
                                        </p>
                                    @else
                                        <p class="text-sm text-slate-500 mt-1">{{ $product->category->name ?? '' }}</p>
                                    @endif
                                </div>
                                <button wire:click="removeItem('{{ $cartKey }}')" class="text-slate-300 hover:text-red-500 transition-colors shrink-0">
                                    <x-heroicon-o-trash class="h-5 w-5" />
                                </button>
                            </div>

                            <div class="flex items-end justify-between mt-4">
                                {{-- Quantity Controls --}}
                                <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                                    <button wire:click="updateQuantity('{{ $cartKey }}', {{ $qty - 1 }})" class="px-3 py-1.5 hover:bg-slate-200 transition-colors text-slate-600 font-medium">−</button>
                                    <span class="px-4 py-1.5 text-sm font-semibold text-slate-800">{{ $qty }}</span>
                                    <button wire:click="updateQuantity('{{ $cartKey }}', {{ $qty + 1 }})" class="px-3 py-1.5 hover:bg-slate-200 transition-colors text-slate-600 font-medium {{ $qty >= $maxStock ? 'opacity-40 cursor-not-allowed' : '' }}" {{ $qty >= $maxStock ? 'disabled' : '' }}>+</button>
                                </div>

                                {{-- Price --}}
                                <div class="text-right">
                                    <span class="text-[#FF6B00] font-bold text-xl">฿{{ number_format($product->price * $qty, 0) }}</span>
                                    @if($qty > 1)
                                        <p class="text-xs text-slate-400">฿{{ number_format($product->price, 0) }} / ชิ้น</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Actions Row --}}
                <div class="flex justify-between items-center pt-2">
                    <a href="{{ route('products') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-[#FF6B00] transition-colors font-medium">
                        <x-heroicon-o-arrow-left class="h-4 w-4" />
                        เลือกซื้อต่อ
                    </a>
                    <button wire:click="clearCart" wire:confirm="ต้องการล้างตะกร้าสินค้าทั้งหมดใช่ไหม?" class="inline-flex items-center gap-1.5 text-sm text-red-400 hover:text-red-600 transition-colors font-medium">
                        <x-heroicon-o-trash class="h-4 w-4" />
                        ล้างตะกร้า
                    </button>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 sticky top-28">
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-slate-800">
                        <x-heroicon-o-receipt-percent class="h-5 w-5 text-[#FF6B00]" />
                        สรุปคำสั่งซื้อ
                    </h2>

                    {{-- Coupon --}}
                    <div class="flex gap-2 mb-6">
                        <input type="text" wire:model="couponCode" placeholder="รหัสคูปอง" class="flex-1 px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FF6B00]/30 focus:border-[#FF6B00]" />
                        <button wire:click="applyCoupon" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-medium transition-colors">ใช้</button>
                    </div>

                    {{-- Price Breakdown --}}
                    <div class="space-y-3 mb-6 border-b border-slate-100 pb-6">
                        <div class="flex justify-between text-slate-600 text-sm">
                            <span>ราคารวม ({{ count($items) }} รายการ)</span>
                            <span>฿{{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600 text-sm">
                            <span>ค่าจัดส่ง</span>
                            @if($shipping === 0)
                                <span class="text-green-500 font-medium">ฟรี</span>
                            @else
                                <span>฿{{ number_format($shipping, 0) }}</span>
                            @endif
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-600">ส่วนลด</span>
                                <span class="text-red-500 font-medium">-฿{{ number_format($discount, 0) }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Total --}}
                    <div class="flex justify-between items-center mb-8">
                        <span class="text-lg font-bold text-slate-800">ราคาสุทธิ</span>
                        <span class="text-2xl font-extrabold text-[#FF6B00]">฿{{ number_format($total, 0) }}</span>
                    </div>

                    {{-- Checkout Button --}}
                    <a href="{{ route('checkout') }}" class="w-full bg-[#FF6B00] hover:bg-orange-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-[#FF6B00]/20 transition-all transform hover:-translate-y-1 mb-4 flex items-center justify-center gap-2">
                        ดำเนินการสั่งซื้อ
                        <x-heroicon-o-arrow-right class="h-5 w-5" />
                    </a>

                    {{-- Trust Badge --}}
                    <div class="mt-4 p-4 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <x-heroicon-o-shield-check class="h-4 w-4 text-[#FF6B00] shrink-0" />
                            <span>รับประกันสินค้าของแท้ 100% และระบบชำระเงินที่ปลอดภัย</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </main>
</div>
