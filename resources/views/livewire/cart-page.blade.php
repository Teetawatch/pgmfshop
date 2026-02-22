<div class="min-h-screen bg-gray-50">
    {{-- Loading overlay for cart actions --}}
    <div wire:loading.delay wire:target="updateQuantity,removeItem,clearCart,applyCoupon" class="fixed inset-0 bg-white/60 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="flex items-center gap-3 bg-white rounded-xl shadow-lg px-6 py-4 border">
            <svg class="h-5 w-5 animate-spin text-[#FF6512]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            <span class="text-sm font-medium text-gray-600">กำลังอัปเดตตะกร้า...</span>
        </div>
    </div>

    <!-- Hero Header -->
    <div class="bg-[#FF6512]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <x-heroicon-o-shopping-cart class="h-6 w-6 text-white" />
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">ตะกร้าสินค้า</h1>
                    <p class="text-white/70 text-sm">{{ count($items) }} รายการ</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @if(count($items) === 0)
        <div class="text-center py-16">
            <x-heroicon-o-shopping-bag class="h-16 w-16 mx-auto text-gray-400 mb-4" />
            <h1 class="text-2xl font-bold mb-2">ตะกร้าว่างเปล่า</h1>
            <p class="text-gray-500 mb-6">ยังไม่มีสินค้าในตะกร้า เลือกซื้อสินค้าเลย!</p>
            <a href="{{ route('products') }}">
                <button class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">เลือกซื้อสินค้า</button>
            </a>
        </div>
    @else
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($items as $item)
                    @php $product = $item['product']; $qty = $item['quantity']; $cartKey = $item['cart_key']; $options = $item['options'] ?? []; $maxStock = $item['max_stock'] ?? $product->stock; $firstImage = is_array($product->images) ? ($product->images[0] ?? '') : ''; @endphp
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <div class="flex gap-4">
                            <div class="w-24 h-24 rounded-lg overflow-hidden bg-gray-100 shrink-0 border border-gray-200">
                                <img src="{{ $firstImage }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('products.show', $product->slug) }}" class="font-medium hover:text-[#FF6512] line-clamp-2">{{ $product->name }}</a>
                                <p class="text-sm text-gray-500 mt-0.5">{{ $product->category->name ?? '' }}</p>
                                @if(!empty($options))
                                    <div class="flex flex-wrap gap-1.5 mt-1">
                                        @if(!empty($options['size']))
                                            <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                                <x-heroicon-o-square-2-stack class="w-3 h-3" />
                                                ไซส์: {{ $options['size'] }}
                                            </span>
                                        @endif
                                        @if(!empty($options['color']))
                                            <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                                <x-heroicon-o-swatch class="w-3 h-3" />
                                                สี: {{ $options['color'] }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center border rounded-md">
                                        <button wire:click="updateQuantity('{{ $cartKey }}', {{ $qty - 1 }})" class="h-8 w-8 flex items-center justify-center hover:bg-gray-50">
                                            <x-heroicon-o-minus class="h-3 w-3" />
                                        </button>
                                        <span class="w-8 text-center text-sm">{{ $qty }}</span>
                                        <button wire:click="updateQuantity('{{ $cartKey }}', {{ $qty + 1 }})" class="h-8 w-8 flex items-center justify-center hover:bg-gray-50 {{ $qty >= $maxStock ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $qty >= $maxStock ? 'disabled' : '' }}>
                                            <x-heroicon-o-plus class="h-3 w-3" />
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="font-bold text-[#FF6512]">฿{{ number_format($product->price * $qty, 0) }}</span>
                                        <button wire:click="removeItem('{{ $cartKey }}')" class="h-8 w-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-md">
                                            <x-heroicon-o-trash class="h-4 w-4" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-between">
                    <a href="{{ route('products') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50 rounded-md transition-colors">
                        <x-heroicon-o-arrow-left class="h-4 w-4" />
                        เลือกซื้อต่อ
                    </a>
                    <button wire:click="clearCart" class="px-4 py-2 text-sm border border-gray-200 rounded-md text-red-500 hover:bg-red-50 transition-colors">ล้างตะกร้า</button>
                </div>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24 space-y-4">
                    <h2 class="font-bold text-lg text-gray-900">สรุปคำสั่งซื้อ</h2>

                    <!-- Coupon -->
                    <div class="flex gap-2">
                        <input type="text" wire:model="couponCode" placeholder="รหัสคูปอง" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
                        <button wire:click="applyCoupon" class="px-4 py-2 border border-gray-200 rounded-lg text-sm hover:bg-gray-50 transition-colors">ใช้</button>
                    </div>

                    <hr class="border-gray-200">

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">ราคาสินค้า</span>
                            <span>฿{{ number_format($subtotal, 0) }}</span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>ส่วนลด</span>
                                <span>-฿{{ number_format($discount, 0) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-500">ค่าจัดส่ง</span>
                            <span>{{ $shipping === 0 ? 'ฟรี' : '฿' . number_format($shipping, 0) }}</span>
                        </div>
                        @if($shipping > 0)
                            <p class="text-xs text-gray-500">คำนวณตามจำนวนสินค้าในตะกร้า</p>
                        @endif
                    </div>

                    <hr class="border-gray-200">

                    <div class="flex justify-between font-bold text-lg">
                        <span>รวมทั้งสิ้น</span>
                        <span class="text-[#FF6512]">฿{{ number_format($total, 0) }}</span>
                    </div>

                    <a href="{{ route('checkout') }}" class="block">
                        <button class="w-full py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">ดำเนินการสั่งซื้อ</button>
                    </a>
                </div>
            </div>
        </div>
    @endif
    </div>
</div>
