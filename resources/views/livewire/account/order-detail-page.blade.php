@php
    $statusColors = [
        'pending'          => 'bg-yellow-100 text-yellow-700',
        'awaiting_payment' => 'bg-orange-100 text-orange-700',
        'paid'             => 'bg-blue-100 text-blue-700',
        'processing'       => 'bg-indigo-100 text-indigo-700',
        'shipped'          => 'bg-purple-100 text-purple-700',
        'delivered'        => 'bg-green-100 text-green-700',
        'cancelled'        => 'bg-red-100 text-red-700',
        'expired'          => 'bg-gray-100 text-gray-600',
    ];
    $statusLabels = [
        'pending'          => 'รอดำเนินการ',
        'awaiting_payment' => 'รอชำระเงิน',
        'paid'             => 'ชำระแล้ว',
        'processing'       => 'กำลังจัดเตรียม',
        'shipped'          => 'จัดส่งแล้ว',
        'delivered'        => 'ส่งสำเร็จ',
        'cancelled'        => 'ยกเลิก',
        'expired'          => 'ไม่ชำระตามกำหนด',
    ];
    $shippingAddr = is_array($order->shipping_address) ? $order->shipping_address : [];
    $paymentLabels = [
        'promptpay'    => 'PromptPay',
        'credit_card'  => 'บัตรเครดิต',
        'bank_transfer'=> 'โอนเงิน',
    ];
    $steps = ['pending', 'paid', 'processing', 'shipped', 'delivered'];
    $stepIcons = ['hourglass_empty', 'payments', 'inventory_2', 'local_shipping', 'check_circle'];
    $currentIndex = array_search($order->status, $steps);
    $isCancelled = $order->status === 'cancelled';
    $isExpired   = $order->status === 'expired';
@endphp

<div class="min-h-screen bg-gray-50">

    {{-- ===== HERO HEADER ===== --}}
    <div class="relative overflow-hidden"
         style="background: linear-gradient(135deg, #1f2937 0%, #374151 100%);">
        <div class="absolute inset-0"
             style="background-image:url(&quot;data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff6b00' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;);">
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-16">
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
                <a href="{{ route('account') }}" class="hover:text-white transition-colors">บัญชีของฉัน</a>
                <span class="material-icons-outlined text-xs">chevron_right</span>
                <a href="{{ route('account.orders') }}" class="hover:text-white transition-colors">คำสั่งซื้อ</a>
                <span class="material-icons-outlined text-xs">chevron_right</span>
                <span class="text-white font-medium">{{ $order->order_number }}</span>
            </div>

            {{-- Title row --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/10 rounded-xl">
                        <span class="material-icons-outlined text-3xl text-white">receipt_long</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white tracking-tight">{{ $order->order_number }}</h1>
                        <p class="text-gray-400 text-sm mt-0.5">
                            สั่งเมื่อ {{ $order->created_at->locale('th')->translatedFormat('j F Y, H:i') }} น.
                        </p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold
                    {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                    <span class="w-2 h-2 rounded-full
                        {{ $isCancelled || $isExpired ? 'bg-red-400' : ($order->status === 'delivered' ? 'bg-green-500' : 'bg-[#ff6b00]') }}">
                    </span>
                    {{ $statusLabels[$order->status] ?? $order->status }}
                </span>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT (overlaps hero) ===== --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 mb-16 relative z-20">

        {{-- Expired Banner --}}
        @if($isExpired)
            <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-6 flex items-start gap-4">
                <div class="p-2 bg-red-100 rounded-full shrink-0">
                    <span class="material-icons-outlined text-red-600">timer_off</span>
                </div>
                <div>
                    <p class="font-semibold text-red-800">ไม่ชำระเงินตามเวลาที่กำหนด</p>
                    <p class="text-sm text-red-600 mt-1">คำสั่งซื้อนี้ถูกยกเลิกอัตโนมัติเนื่องจากไม่ได้ชำระเงินภายใน 24 ชั่วโมงหลังจากสั่งซื้อ สต็อกสินค้าได้ถูกคืนเรียบร้อยแล้ว</p>
                </div>
            </div>
        @endif

        {{-- Awaiting Payment Countdown --}}
        @if($order->status === 'awaiting_payment' && $order->payment_deadline)
            <div x-data="paymentCountdown('{{ $order->payment_deadline->toIso8601String() }}')"
                 class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-6">
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-amber-100 rounded-full shrink-0">
                        <span class="material-icons-outlined text-amber-600">schedule</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-amber-800">กรุณาชำระเงินภายในเวลาที่กำหนด</p>
                        <p class="text-sm text-amber-600 mt-1">
                            ชำระภายใน {{ $order->payment_deadline->locale('th')->translatedFormat('j F Y, H:i') }} น.
                            หากไม่ชำระตามกำหนด คำสั่งซื้อจะถูกยกเลิกอัตโนมัติ
                        </p>
                    </div>
                </div>
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
                    <p class="font-semibold text-red-600">หมดเวลาชำระเงินแล้ว</p>
                    <p class="text-sm text-red-500 mt-1">คำสั่งซื้อจะถูกยกเลิกอัตโนมัติในอีกสักครู่</p>
                </div>
            </div>
        @endif

        {{-- Progress Steps --}}
        @if(!$isCancelled && !$isExpired)
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between relative">
                    {{-- Connector line --}}
                    <div class="absolute top-5 left-0 right-0 h-0.5 bg-gray-200 z-0 mx-8"></div>
                    @foreach($steps as $i => $step)
                        @php $done = $currentIndex !== false && $i <= $currentIndex; @endphp
                        <div class="flex flex-col items-center gap-2 z-10 flex-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-sm transition-all
                                {{ $done ? 'bg-[#ff6b00] text-white' : 'bg-gray-100 text-gray-400' }}">
                                <span class="material-icons-outlined text-lg">{{ $stepIcons[$i] }}</span>
                            </div>
                            <span class="text-xs font-medium text-center leading-tight
                                {{ $done ? 'text-[#ff6b00]' : 'text-gray-400' }}">
                                {{ $statusLabels[$step] ?? $step }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Cancelled Banner --}}
        @if($isCancelled)
            <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-6 flex items-start gap-4">
                <div class="p-2 bg-red-100 rounded-full shrink-0">
                    <span class="material-icons-outlined text-red-600">cancel</span>
                </div>
                <div>
                    <p class="font-semibold text-red-800">คำสั่งซื้อถูกยกเลิก</p>
                    @if($order->cancel_reason)
                        <p class="text-sm text-red-600 mt-1">เหตุผล: {{ $order->cancel_reason }}</p>
                    @endif
                </div>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6">

            {{-- ===== LEFT: Order Items ===== --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Items Card --}}
                <section class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="flex items-center gap-4 p-6 border-b border-gray-100">
                        <div class="p-3 bg-orange-100 rounded-full text-[#ff6b00]">
                            <span class="material-icons-outlined text-2xl">inventory_2</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">รายการสินค้า</h2>
                            <p class="text-sm text-gray-400">{{ $order->items->count() }} รายการ</p>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                            <div class="flex gap-4 p-5 items-center">
                                <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 shrink-0 border border-gray-200">
                                    @if($item->product_image)
                                        <img src="{{ $item->product_image }}"
                                             alt="{{ $item->product_name }}"
                                             class="w-full h-full object-cover" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <span class="material-icons-outlined text-3xl">image_not_supported</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900">{{ $item->product_name }}</p>
                                    @if(!empty($item->options))
                                        <div class="flex flex-wrap gap-2 mt-1.5">
                                            @if(!empty($item->options['size']))
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-md">
                                                    <span class="material-icons-outlined text-xs">straighten</span>
                                                    {{ $item->options['size'] }}
                                                </span>
                                            @endif
                                            @if(!empty($item->options['color']))
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-md">
                                                    <span class="material-icons-outlined text-xs">palette</span>
                                                    {{ $item->options['color'] }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="flex items-center justify-between mt-3">
                                        <span class="text-sm text-gray-400">฿{{ number_format($item->price, 0) }} × {{ $item->quantity }}</span>
                                        <span class="text-base font-bold text-gray-900">฿{{ number_format($item->price * $item->quantity, 0) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Payment Summary (inside left col on mobile too) --}}
                <section class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="flex items-center gap-4 p-6 border-b border-gray-100">
                        <div class="p-3 bg-orange-100 rounded-full text-[#ff6b00]">
                            <span class="material-icons-outlined text-2xl">receipt</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">สรุปการชำระเงิน</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">ราคาสินค้า</span>
                            <span class="font-medium text-gray-900">฿{{ number_format($order->subtotal, 0) }}</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">ส่วนลด</span>
                                <span class="font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded">-฿{{ number_format($order->discount, 0) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">ค่าจัดส่ง</span>
                            <span class="font-medium text-gray-900">
                                {{ $order->shipping_cost > 0 ? '฿'.number_format($order->shipping_cost, 0) : 'ฟรี' }}
                            </span>
                        </div>
                        <div class="border-t border-gray-100 pt-3 flex justify-between items-center">
                            <span class="font-bold text-gray-900">รวมทั้งสิ้น</span>
                            <span class="text-xl font-bold text-[#ff6b00]">฿{{ number_format($order->total, 0) }}</span>
                        </div>
                        <div class="flex items-center gap-2 pt-1 text-xs text-gray-400 bg-gray-50 rounded-lg px-3 py-2.5">
                            <span class="material-icons-outlined text-sm">payment</span>
                            ชำระผ่าน: {{ $paymentLabels[$order->payment_method] ?? $order->payment_method }}
                        </div>
                    </div>
                </section>

            </div>

            {{-- ===== RIGHT: Sidebar ===== --}}
            <div class="space-y-6">

                {{-- Shipping Address --}}
                <section class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="flex items-center gap-4 p-6 border-b border-gray-100">
                        <div class="p-3 bg-orange-100 rounded-full text-[#ff6b00]">
                            <span class="material-icons-outlined text-2xl">home_work</span>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">ที่อยู่จัดส่ง</h3>
                            <p class="text-xs text-gray-400">ข้อมูลการจัดส่ง</p>
                        </div>
                    </div>
                    <div class="p-5 space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                                <span class="material-icons-outlined text-gray-500 text-lg">person</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $shippingAddr['name'] ?? '-' }}</p>
                                <p class="text-sm text-gray-500 mt-0.5">{{ $shippingAddr['phone'] ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-sm text-gray-600 leading-relaxed">
                                {{ $shippingAddr['address'] ?? '' }}@if($shippingAddr['district'] ?? ''), {{ $shippingAddr['district'] }}@endif@if($shippingAddr['province'] ?? ''), {{ $shippingAddr['province'] }}@endif {{ $shippingAddr['postal_code'] ?? '' }}
                            </p>
                        </div>
                    </div>
                </section>

                {{-- Tracking --}}
                @if($order->tracking_number)
                    <section class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="flex items-center gap-4 p-6 border-b border-gray-100">
                            <div class="p-3 bg-orange-100 rounded-full text-[#ff6b00]">
                                <span class="material-icons-outlined text-2xl">local_shipping</span>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">ข้อมูลจัดส่ง</h3>
                                <p class="text-xs text-gray-400">เลขพัสดุและการติดตาม</p>
                            </div>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-xs text-gray-400 mb-1">เลขพัสดุ</p>
                                <p class="font-mono font-bold text-gray-900 tracking-wider">{{ $order->tracking_number }}</p>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <span class="material-icons-outlined text-base">mail</span>
                                ไปรษณีย์ไทย
                            </div>
                            <div class="flex flex-col gap-3">
                                <a href="{{ route('account.orders.tracking', $order->id) }}"
                                   class="flex items-center justify-center gap-2 py-2.5 px-4 bg-[#ff6b00] text-white font-semibold rounded-lg hover:bg-orange-600 transition-colors shadow-sm">
                                    <span class="material-icons-outlined text-lg">track_changes</span>
                                    ติดตามพัสดุ
                                </a>
                                <a href="https://track.thailandpost.co.th/?trackNumber={{ urlencode($order->tracking_number) }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="flex items-center justify-center gap-2 py-2.5 px-4 border border-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-lg">open_in_new</span>
                                    เช็คที่ไปรษณีย์ไทย
                                </a>
                            </div>
                        </div>
                    </section>
                @endif

                {{-- Back button --}}
                <a href="{{ route('account.orders') }}"
                   class="flex items-center justify-center gap-2 w-full px-6 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors shadow-sm">
                    <span class="material-icons-outlined text-base">arrow_back</span>
                    กลับไปหน้าคำสั่งซื้อ
                </a>

            </div>
        </div>
    </main>
</div>
