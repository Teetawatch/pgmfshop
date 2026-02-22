@php
    $statusColors = [
        'pending' => 'bg-yellow-50 text-yellow-700',
        'awaiting_payment' => 'bg-orange-50 text-orange-700',
        'paid' => 'bg-blue-50 text-blue-700',
        'processing' => 'bg-indigo-50 text-indigo-700',
        'shipped' => 'bg-purple-50 text-purple-700',
        'delivered' => 'bg-green-50 text-green-700',
        'cancelled' => 'bg-red-50 text-red-700',
        'expired' => 'bg-gray-100 text-gray-600',
    ];
    $statusLabels = [
        'pending' => 'รอดำเนินการ',
        'awaiting_payment' => 'รอชำระเงิน',
        'paid' => 'ชำระแล้ว',
        'processing' => 'กำลังจัดเตรียม',
        'shipped' => 'จัดส่งแล้ว',
        'delivered' => 'ส่งสำเร็จ',
        'cancelled' => 'ยกเลิก',
        'expired' => 'ไม่ชำระตามกำหนด',
    ];
    $shippingAddr = is_array($order->shipping_address) ? $order->shipping_address : [];
    $paymentLabels = [
        'promptpay' => 'PromptPay',
        'credit_card' => 'บัตรเครดิต',
        'bank_transfer' => 'โอนเงิน',
    ];
    $steps = ['pending', 'paid', 'processing', 'shipped', 'delivered'];
    $currentIndex = array_search($order->status, $steps);
    $isCancelled = $order->status === 'cancelled';
    $isExpired = $order->status === 'expired';
@endphp

<div class="min-h-screen bg-gray-50">
    <!-- Hero Header -->
    <div class="bg-[#FF6512]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-sm text-white/70 mb-4">
                <a href="{{ route('account') }}" class="hover:text-white transition-colors">บัญชีของฉัน</a>
                <x-heroicon-o-chevron-right class="h-3 w-3" />
                <a href="{{ route('account.orders') }}" class="hover:text-white transition-colors">คำสั่งซื้อ</a>
                <x-heroicon-o-chevron-right class="h-3 w-3" />
                <span class="text-white font-medium">{{ $order->order_number }}</span>
            </div>
            
            <!-- Header Content -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <x-heroicon-o-clipboard-document-list class="h-6 w-6 text-white" />
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">{{ $order->order_number }}</h1>
                        <p class="text-white/70 text-sm">สั่งเมื่อ {{ $order->created_at->locale('th')->translatedFormat('j F Y, H:i') }} น.</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white">
                    <span class="w-2 h-2 rounded-full bg-white"></span>
                    {{ $statusLabels[$order->status] ?? $order->status }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Expired Banner -->
        @if($isExpired)
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-6 flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                    <x-heroicon-o-clock class="h-5 w-5 text-red-600" />
                </div>
                <div>
                    <p class="text-base font-semibold text-red-800">ไม่ชำระเงินตามเวลาที่กำหนด</p>
                    <p class="text-sm text-red-600 mt-1">คำสั่งซื้อนี้ถูกยกเลิกอัตโนมัติเนื่องจากไม่ได้ชำระเงินภายใน 24 ชั่วโมงหลังจากสั่งซื้อ สต็อกสินค้าได้ถูกคืนเรียบร้อยแล้ว</p>
                </div>
            </div>
        @endif

        <!-- Payment Deadline Warning with Countdown -->
        @if($order->status === 'awaiting_payment' && $order->payment_deadline)
            <div x-data="paymentCountdown('{{ $order->payment_deadline->toIso8601String() }}')" class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                        <x-heroicon-o-clock class="h-5 w-5 text-amber-600" />
                    </div>
                    <div class="flex-1">
                        <p class="text-base font-semibold text-amber-800">กรุณาชำระเงินภายในเวลาที่กำหนด</p>
                        <p class="text-sm text-amber-600 mt-1">ชำระภายใน {{ $order->payment_deadline->locale('th')->translatedFormat('j F Y, H:i') }} น. หากไม่ชำระตามกำหนด คำสั่งซื้อจะถูกยกเลิกอัตโนมัติ</p>
                    </div>
                </div>
                <!-- Countdown Timer -->
                <div class="mt-4 flex items-center justify-center gap-4" x-show="!expired">
                    <div class="text-center">
                        <div class="bg-white border border-amber-200 rounded-lg px-4 py-3 min-w-[64px] shadow-sm">
                            <span class="text-2xl font-bold font-mono text-amber-700" x-text="hours">00</span>
                        </div>
                        <p class="text-xs text-amber-600 mt-1">ชั่วโมง</p>
                    </div>
                    <span class="text-2xl font-bold text-amber-400 -mt-4">:</span>
                    <div class="text-center">
                        <div class="bg-white border border-amber-200 rounded-lg px-4 py-3 min-w-[64px] shadow-sm">
                            <span class="text-2xl font-bold font-mono text-amber-700" x-text="minutes">00</span>
                        </div>
                        <p class="text-xs text-amber-600 mt-1">นาที</p>
                    </div>
                    <span class="text-2xl font-bold text-amber-400 -mt-4">:</span>
                    <div class="text-center">
                        <div class="bg-white border border-amber-200 rounded-lg px-4 py-3 min-w-[64px] shadow-sm">
                            <span class="text-2xl font-bold font-mono text-amber-700" x-text="seconds">00</span>
                        </div>
                        <p class="text-xs text-amber-600 mt-1">วินาที</p>
                    </div>
                </div>
                <div x-show="expired" class="mt-4 text-center">
                    <p class="text-base font-semibold text-red-600">หมดเวลาชำระเงินแล้ว</p>
                    <p class="text-sm text-red-500 mt-1">คำสั่งซื้อจะถูกยกเลิกอัตโนมัติในอีกสักครู่</p>
                </div>
            </div>
        @endif

        <!-- Progress Bar -->
        @if(!$isCancelled && !$isExpired)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center gap-2">
                    @foreach($steps as $i => $step)
                        <div class="flex-1 h-2 rounded-full {{ $currentIndex !== false && $i <= $currentIndex ? 'bg-[#FF6512]' : 'bg-gray-200' }}"></div>
                    @endforeach
                </div>
                <div class="flex justify-between mt-3">
                    @foreach($steps as $i => $step)
                        <span class="text-xs font-medium {{ $currentIndex !== false && $i <= $currentIndex ? 'text-[#FF6512]' : 'text-gray-400' }}">
                            {{ $statusLabels[$step] ?? $step }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#FF6512] flex items-center justify-center">
                                <x-heroicon-o-cube class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-gray-900">รายการสินค้า</h2>
                                <p class="text-sm text-gray-500">{{ $order->items->count() }} รายการ</p>
                            </div>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                            <div class="px-6 py-5 flex gap-4 items-center">
                                <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 shrink-0 border border-gray-200">
                                    @if($item->product_image)
                                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <x-heroicon-o-cube class="h-8 w-8" />
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-base font-semibold text-gray-900">{{ $item->product_name }}</p>
                                    @if(!empty($item->options))
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            @if(!empty($item->options['size']))
                                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-md">
                                                    <x-heroicon-o-square-2-stack class="w-3 h-3 mr-1" />
                                                    {{ $item->options['size'] }}
                                                </span>
                                            @endif
                                            @if(!empty($item->options['color']))
                                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-md">
                                                    <x-heroicon-o-swatch class="w-3 h-3 mr-1" />
                                                    {{ $item->options['color'] }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="flex items-center justify-between mt-3">
                                        <span class="text-sm text-gray-500">฿{{ number_format($item->price, 0) }} x {{ $item->quantity }}</span>
                                        <span class="text-base font-bold text-gray-900">฿{{ number_format($item->price * $item->quantity, 0) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Shipping -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#FF6512] flex items-center justify-center">
                                <x-heroicon-o-map-pin class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">ที่อยู่จัดส่ง</h3>
                                <p class="text-sm text-gray-500">ข้อมูลการจัดส่ง</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                                <x-heroicon-o-user class="h-5 w-5 text-gray-600" />
                            </div>
                            <div>
                                <p class="text-base font-semibold text-gray-900">{{ $shippingAddr['name'] ?? '-' }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $shippingAddr['phone'] ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-sm text-gray-700">
                                {{ $shippingAddr['address'] ?? '' }}@if($shippingAddr['district'] ?? ''), {{ $shippingAddr['district'] }}@endif @if($shippingAddr['province'] ?? ''), {{ $shippingAddr['province'] }}@endif {{ $shippingAddr['postal_code'] ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tracking -->
                @if($order->tracking_number)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-[#FF6512] flex items-center justify-center">
                                    <x-heroicon-o-truck class="h-5 w-5 text-white" />
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900">ข้อมูลจัดส่ง</h3>
                                    <p class="text-sm text-gray-500">เลขพัสดุและการติดตาม</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-sm text-gray-500 mb-1">เลขพัสดุ</p>
                                <p class="text-base font-mono font-bold text-gray-900">{{ $order->tracking_number }}</p>
                            </div>
                            @if($order->shipping_method)
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-gray-500">ขนส่ง:</span>
                                    <span class="font-medium text-gray-900">ไปรษณีย์ไทย</span>
                                </div>
                            @endif
                            <div class="flex flex-col gap-3">
                                <a href="{{ route('account.orders.tracking', $order->id) }}" class="flex items-center justify-center gap-2 py-3 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                    <x-heroicon-o-truck class="h-5 w-5" />
                                    ติดตามพัสดุ
                                </a>
                                <a href="https://track.thailandpost.co.th/?trackNumber={{ urlencode($order->tracking_number) }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 py-3 px-4 border border-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                                    <x-heroicon-o-globe-alt class="h-5 w-5" />
                                    เช็คที่ไปรษณีย์ไทย
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Payment Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#FF6512] flex items-center justify-center">
                                <x-heroicon-o-credit-card class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">สรุปการชำระเงิน</h3>
                                <p class="text-sm text-gray-500">รายละเอียดการชำระ</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">ราคาสินค้า</span>
                            <span class="font-medium text-gray-900">฿{{ number_format($order->subtotal, 0) }}</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">ส่วนลด</span>
                                <span class="font-semibold text-red-600 bg-red-50 px-2 py-1 rounded">-฿{{ number_format($order->discount, 0) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">ค่าจัดส่ง</span>
                            <span class="font-medium text-gray-900">{{ $order->shipping_cost > 0 ? '฿' . number_format($order->shipping_cost, 0) : 'ฟรี' }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between">
                                <span class="text-base font-bold text-gray-900">รวมทั้งสิ้น</span>
                                <span class="text-lg font-bold text-[#FF6512] bg-orange-50 px-3 py-1 rounded-lg">฿{{ number_format($order->total, 0) }}</span>
                            </div>
                        </div>
                        <div class="pt-2 text-xs text-gray-500 bg-gray-50 rounded-lg p-3">
                            ชำระผ่าน: {{ $paymentLabels[$order->payment_method] ?? $order->payment_method }}
                        </div>
                    </div>
                </div>

                <a href="{{ route('account.orders') }}" class="flex items-center justify-center gap-2 w-full px-6 py-3 border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                    <x-heroicon-o-arrow-left class="h-4 w-4" />
                    กลับไปหน้าคำสั่งซื้อ
                </a>
            </div>
        </div>
    </div>
</div>
