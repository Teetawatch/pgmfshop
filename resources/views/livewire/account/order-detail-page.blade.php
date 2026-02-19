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

<div class="container mx-auto px-4 py-8 max-w-7xl">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('account') }}" class="hover:text-gray-900 transition-colors">บัญชีของฉัน</a>
        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
        <a href="{{ route('account.orders') }}" class="hover:text-gray-900 transition-colors">คำสั่งซื้อ</a>
        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
        <span class="text-gray-900 font-medium">{{ $order->order_number }}</span>
    </div>

    {{-- Order Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-bold">{{ $order->order_number }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">สั่งเมื่อ {{ $order->created_at->locale('th')->translatedFormat('j F Y, H:i') }} น.</p>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium {{ $statusColors[$order->status] ?? 'bg-gray-50 text-gray-700' }}">
            {{ $statusLabels[$order->status] ?? $order->status }}
        </span>
    </div>

    {{-- Expired Banner --}}
    @if($isExpired)
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 flex items-start gap-3">
            <svg class="h-5 w-5 text-gray-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <div>
                <p class="text-sm font-semibold text-gray-700">ไม่ชำระเงินตามเวลาที่กำหนด</p>
                <p class="text-xs text-gray-500 mt-0.5">คำสั่งซื้อนี้ถูกยกเลิกอัตโนมัติเนื่องจากไม่ได้ชำระเงินภายใน 24 ชั่วโมงหลังจากสั่งซื้อ สต็อกสินค้าได้ถูกคืนเรียบร้อยแล้ว</p>
            </div>
        </div>
    @endif

    {{-- Payment Deadline Warning with Countdown --}}
    @if($order->status === 'awaiting_payment' && $order->payment_deadline)
        <div x-data="paymentCountdown('{{ $order->payment_deadline->toIso8601String() }}')" class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="h-5 w-5 text-orange-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-orange-700">กรุณาชำระเงินภายในเวลาที่กำหนด</p>
                    <p class="text-xs text-orange-600 mt-0.5">ชำระภายใน {{ $order->payment_deadline->locale('th')->translatedFormat('j F Y, H:i') }} น. หากไม่ชำระตามกำหนด คำสั่งซื้อจะถูกยกเลิกอัตโนมัติ</p>
                </div>
            </div>
            {{-- Countdown Timer --}}
            <div class="mt-3 flex items-center justify-center gap-3" x-show="!expired">
                <div class="text-center">
                    <div class="bg-white border border-orange-200 rounded-lg px-3 py-2 min-w-[56px] shadow-sm">
                        <span class="text-xl font-bold font-mono text-orange-700" x-text="hours">00</span>
                    </div>
                    <p class="text-[10px] text-orange-500 mt-1">ชั่วโมง</p>
                </div>
                <span class="text-xl font-bold text-orange-400 -mt-4">:</span>
                <div class="text-center">
                    <div class="bg-white border border-orange-200 rounded-lg px-3 py-2 min-w-[56px] shadow-sm">
                        <span class="text-xl font-bold font-mono text-orange-700" x-text="minutes">00</span>
                    </div>
                    <p class="text-[10px] text-orange-500 mt-1">นาที</p>
                </div>
                <span class="text-xl font-bold text-orange-400 -mt-4">:</span>
                <div class="text-center">
                    <div class="bg-white border border-orange-200 rounded-lg px-3 py-2 min-w-[56px] shadow-sm">
                        <span class="text-xl font-bold font-mono text-orange-700" x-text="seconds">00</span>
                    </div>
                    <p class="text-[10px] text-orange-500 mt-1">วินาที</p>
                </div>
            </div>
            <div x-show="expired" class="mt-3 text-center">
                <p class="text-sm font-semibold text-red-600">หมดเวลาชำระเงินแล้ว</p>
                <p class="text-xs text-red-500 mt-0.5">คำสั่งซื้อจะถูกยกเลิกอัตโนมัติในอีกสักครู่</p>
            </div>
        </div>
    @endif

    {{-- Progress Bar --}}
    @if(!$isCancelled && !$isExpired)
        <div class="bg-white rounded-lg border p-5 mb-6">
            <div class="flex items-center gap-1">
                @foreach($steps as $i => $step)
                    <div class="flex-1 h-1.5 rounded-full {{ $currentIndex !== false && $i <= $currentIndex ? 'bg-[hsl(var(--primary))]' : 'bg-gray-100' }}"></div>
                @endforeach
            </div>
            <div class="flex justify-between mt-2">
                @foreach($steps as $i => $step)
                    <span class="text-[10px] font-medium {{ $currentIndex !== false && $i <= $currentIndex ? 'text-[hsl(var(--primary))]' : 'text-gray-400' }}">
                        {{ $statusLabels[$step] ?? $step }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Order Items --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg border">
                <div class="px-5 py-4 border-b">
                    <h2 class="font-bold">รายการสินค้า</h2>
                </div>
                <div class="divide-y">
                    @foreach($order->items as $item)
                        <div class="px-5 py-4 flex gap-4 items-center">
                            <div class="w-16 h-16 rounded-md overflow-hidden bg-gray-100 shrink-0">
                                @if($item->product_image)
                                    <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium">{{ $item->product_name }}</p>
                                @if(!empty($item->options))
                                    <p class="text-[10px] text-gray-400 mt-0.5">
                                        @if(!empty($item->options['size']))ไซส์: {{ $item->options['size'] }}@endif
                                        @if(!empty($item->options['size']) && !empty($item->options['color'])) · @endif
                                        @if(!empty($item->options['color']))สี: {{ $item->options['color'] }}@endif
                                    </p>
                                @endif
                                <p class="text-xs text-gray-500 mt-0.5">฿{{ number_format($item->price, 0) }} x {{ $item->quantity }}</p>
                            </div>
                            <p class="text-sm font-bold shrink-0">฿{{ number_format($item->price * $item->quantity, 0) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Shipping --}}
            <div class="bg-white rounded-lg border">
                <div class="px-5 py-4 border-b">
                    <h2 class="font-bold flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        ที่อยู่จัดส่ง
                    </h2>
                </div>
                <div class="p-5 text-sm space-y-1">
                    <p class="font-medium">{{ $shippingAddr['name'] ?? '-' }}</p>
                    <p class="text-gray-500">{{ $shippingAddr['phone'] ?? '-' }}</p>
                    <p class="text-gray-500">
                        {{ $shippingAddr['address'] ?? '' }}@if($shippingAddr['district'] ?? ''), {{ $shippingAddr['district'] }}@endif @if($shippingAddr['province'] ?? ''), {{ $shippingAddr['province'] }}@endif {{ $shippingAddr['postal_code'] ?? '' }}
                    </p>
                </div>
            </div>

            {{-- Tracking --}}
            @if($order->tracking_number)
                <div class="bg-white rounded-lg border">
                    <div class="px-5 py-4 border-b">
                        <h2 class="font-bold flex items-center gap-2">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                            ข้อมูลจัดส่ง
                        </h2>
                    </div>
                    <div class="p-5 text-sm space-y-3">
                        <p><span class="text-gray-500">เลขพัสดุ: </span><span class="font-mono font-bold">{{ $order->tracking_number }}</span></p>
                        @if($order->shipping_method)
                            <p><span class="text-gray-500">ขนส่ง: </span><span>ไปรษณีย์ไทย</span></p>
                        @endif
                        <div class="flex flex-col gap-2 pt-1">
                            <a href="{{ route('account.orders.tracking', $order->id) }}" class="flex items-center justify-center gap-2 py-2 px-3 bg-purple-600 text-white text-xs font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                ติดตามพัสดุ
                            </a>
                            <a href="https://track.thailandpost.co.th/?trackNumber={{ urlencode($order->tracking_number) }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 py-2 px-3 bg-orange-500 text-white text-xs font-semibold rounded-lg hover:bg-orange-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                เช็คที่ไปรษณีย์ไทย
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Payment Summary --}}
            <div class="bg-white rounded-lg border">
                <div class="px-5 py-4 border-b">
                    <h2 class="font-bold">สรุปการชำระเงิน</h2>
                </div>
                <div class="p-5 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">ราคาสินค้า</span>
                        <span>฿{{ number_format($order->subtotal, 0) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>ส่วนลด</span>
                            <span>-฿{{ number_format($order->discount, 0) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">ค่าจัดส่ง</span>
                        <span>{{ $order->shipping_cost > 0 ? '฿' . number_format($order->shipping_cost, 0) : 'ฟรี' }}</span>
                    </div>
                    <hr>
                    <div class="flex justify-between font-bold text-base">
                        <span>รวมทั้งสิ้น</span>
                        <span>฿{{ number_format($order->total, 0) }}</span>
                    </div>
                    <div class="pt-2 text-xs text-gray-500">
                        ชำระผ่าน: {{ $paymentLabels[$order->payment_method] ?? $order->payment_method }}
                    </div>
                </div>
            </div>

            <a href="{{ route('account.orders') }}" class="flex items-center justify-center gap-2 w-full px-4 py-2 border rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                กลับไปหน้าคำสั่งซื้อ
            </a>
        </div>
    </div>
</div>
