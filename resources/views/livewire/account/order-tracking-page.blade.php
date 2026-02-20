@php
    $statusColors = [
        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
        'awaiting_payment' => 'bg-orange-50 text-orange-700 border-orange-200',
        'paid' => 'bg-blue-50 text-blue-700 border-blue-200',
        'processing' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'shipped' => 'bg-purple-50 text-purple-700 border-purple-200',
        'delivered' => 'bg-green-50 text-green-700 border-green-200',
        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
        'expired' => 'bg-gray-100 text-gray-600 border-gray-200',
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
    $statusIcons = [
        'pending' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
        'awaiting_payment' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>',
        'paid' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
        'processing' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
        'shipped' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>',
        'delivered' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
        'cancelled' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
        'expired' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
    ];
    $history = $order->status_history ?? [];
    $shippingAddr = is_array($order->shipping_address) ? $order->shipping_address : [];
@endphp

<div class="min-h-screen bg-gray-50">
    <!-- Hero Header -->
    <div class="bg-[#FF6512]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-sm text-white/70 mb-4">
                <a href="{{ route('account') }}" class="hover:text-white transition-colors">บัญชีของฉัน</a>
                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('account.orders') }}" class="hover:text-white transition-colors">คำสั่งซื้อ</a>
                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('account.orders.show', $order->id) }}" class="hover:text-white transition-colors">{{ $order->order_number }}</a>
                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                <span class="text-white font-medium">ติดตามพัสดุ</span>
            </div>
            
            <!-- Header Content -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">ติดตามพัสดุ</h1>
                        <p class="text-white/70 text-sm">คำสั่งซื้อ #{{ $order->order_number }}</p>
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tracking Number Card -->
                @if($order->tracking_number)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="bg-[#FF6512] px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-medium">เลขพัสดุ (ไปรษณีย์ไทย)</p>
                                <p class="text-xl font-bold font-mono tracking-wider text-white">{{ $order->tracking_number }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 flex flex-col sm:flex-row gap-3">
                        <a href="{{ $thaiPostUrl }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-2 py-3 px-4 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            ตรวจสอบสถานะที่ไปรษณีย์ไทย
                        </a>
                        <button onclick="navigator.clipboard.writeText('{{ $order->tracking_number }}').then(() => { this.querySelector('span').textContent = 'คัดลอกแล้ว!'; setTimeout(() => { this.querySelector('span').textContent = 'คัดลอกเลขพัสดุ'; }, 2000); })" class="flex items-center justify-center gap-2 py-3 px-4 border border-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            <span>คัดลอกเลขพัสดุ</span>
                        </button>
                    </div>
                </div>
                @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-lg bg-gray-100 flex items-center justify-center">
                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-1">ยังไม่มีเลขพัสดุ</h3>
                    <p class="text-sm text-gray-500">เลขพัสดุจะแสดงเมื่อร้านค้าจัดส่งสินค้าแล้ว</p>
                    @if($order->status === 'processing')
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-orange-50 text-[#FF6512] font-medium rounded-lg">
                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            กำลังจัดเตรียมสินค้า
                        </div>
                    @endif
                </div>
            @endif

                <!-- Status Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#FF6512] flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-gray-900">ประวัติสถานะ</h2>
                                <p class="text-sm text-gray-500">การเปลี่ยนแปลงสถานะออเดอร์</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @if(count($history) > 0)
                            <div class="relative">
                                @foreach(array_reverse($history) as $idx => $entry)
                                    @php
                                        $isFirst = $idx === 0;
                                        $entryStatus = $entry['status'] ?? '';
                                        $entryNote = $entry['note'] ?? '';
                                        $entryTime = isset($entry['timestamp']) ? \Carbon\Carbon::parse($entry['timestamp']) : null;
                                        $colorClass = $isFirst ? 'bg-[#FF6512] text-white' : 'bg-gray-200 text-gray-600';
                                    @endphp
                                    <div class="flex gap-4 {{ !$loop->last ? 'pb-6' : '' }} relative">
                                        {{-- Timeline line --}}
                                        @if(!$loop->last)
                                            <div class="absolute left-[15px] top-8 bottom-0 w-0.5 bg-gray-200"></div>
                                        @endif
                                        {{-- Icon --}}
                                        <div class="w-8 h-8 rounded-full {{ $colorClass }} flex items-center justify-center shrink-0 z-10">
                                            @if($isFirst)
                                                <div class="w-4 h-4">{!! $statusIcons[$entryStatus] ?? '<svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg>' !!}</div>
                                            @else
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                            @endif
                                        </div>
                                        {{-- Content --}}
                                        <div class="flex-1 min-w-0 pt-0.5">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="text-sm font-semibold {{ $isFirst ? 'text-gray-900' : 'text-gray-600' }}">
                                                    {{ $statusLabels[$entryStatus] ?? $entryStatus }}
                                                </span>
                                                @if($isFirst)
                                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-orange-50 text-[#FF6512]">ล่าสุด</span>
                                                @endif
                                            </div>
                                            @if($entryNote)
                                                <p class="text-sm text-gray-500 mt-0.5">{{ $entryNote }}</p>
                                            @endif
                                            @if($entryTime)
                                                <p class="text-xs text-gray-400 mt-1">{{ $entryTime->locale('th')->translatedFormat('j F Y, H:i') }} น.</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <p class="text-sm text-gray-400">ยังไม่มีประวัติสถานะ</p>
                            </div>
                        @endif
                    </div>
                </div>
        </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Order Items Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#FF6512] flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">สินค้าในคำสั่งซื้อ</h3>
                                <p class="text-sm text-gray-500">{{ $order->items->count() }} รายการ</p>
                            </div>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                            <div class="px-5 py-3 flex gap-3 items-center">
                                <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 shrink-0 border border-gray-200">
                                    @if($item->product_image)
                                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" />
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-800 line-clamp-1">{{ $item->product_name }}</p>
                                    @if(!empty($item->options))
                                        <p class="text-[10px] text-gray-400">
                                            @if(!empty($item->options['size']))ไซส์: {{ $item->options['size'] }}@endif
                                            @if(!empty($item->options['size']) && !empty($item->options['color'])) · @endif
                                            @if(!empty($item->options['color']))สี: {{ $item->options['color'] }}@endif
                                        </p>
                                    @endif
                                    <p class="text-[11px] text-gray-400">x{{ $item->quantity }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#FF6512] flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">ที่อยู่จัดส่ง</h3>
                                <p class="text-sm text-gray-500">ข้อมูลการจัดส่ง</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 text-sm space-y-1">
                        <p class="font-medium">{{ $shippingAddr['name'] ?? '-' }}</p>
                        <p class="text-gray-500">{{ $shippingAddr['phone'] ?? '-' }}</p>
                        <p class="text-gray-500">
                            {{ $shippingAddr['address'] ?? '' }}@if($shippingAddr['district'] ?? ''), {{ $shippingAddr['district'] }}@endif @if($shippingAddr['province'] ?? ''), {{ $shippingAddr['province'] }}@endif {{ $shippingAddr['postal_code'] ?? '' }}
                        </p>
                    </div>
                </div>

                <!-- Thai Post Info -->
                <div class="bg-orange-50 rounded-xl border border-orange-200 p-5">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5 text-[#FF6512]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-orange-800">จัดส่งโดย ไปรษณีย์ไทย</p>
                            <p class="text-xs text-orange-600 mt-1">ระยะเวลาจัดส่งโดยประมาณ 1-3 วันทำการ</p>
                            <a href="https://www.thailandpost.co.th" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-xs text-[#FF6512] font-medium mt-2 hover:underline">
                                เว็บไซต์ไปรษณีย์ไทย
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Back Links -->
                <div class="space-y-2">
                    <a href="{{ route('account.orders.show', $order->id) }}" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        ดูรายละเอียดคำสั่งซื้อ
                    </a>
                    <a href="{{ route('account.orders') }}" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                        กลับไปหน้าคำสั่งซื้อ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
