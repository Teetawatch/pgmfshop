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
    <div class="bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-800">
        <div class="container mx-auto px-4 py-12">
            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-sm text-purple-200 mb-6">
                <a href="{{ route('account') }}" class="hover:text-white transition-colors">บัญชีของฉัน</a>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('account.orders') }}" class="hover:text-white transition-colors">คำสั่งซื้อ</a>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                <a href="{{ route('account.orders.show', $order->id) }}" class="hover:text-white transition-colors">{{ $order->order_number }}</a>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                <span class="text-white font-medium">ติดตามพัสดุ</span>
            </div>
            
            <!-- Header Content -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">ติดตามพัสดุ</h1>
                        <p class="text-purple-100">คำสั่งซื้อ #{{ $order->order_number }}</p>
                        <div class="flex items-center gap-3 mt-3">
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30">
                                <span class="w-2 h-2 rounded-full bg-white"></span>
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                            <p class="text-purple-200 text-sm">
                                <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="10" rx="2" ry="2"/><circle cx="12" cy="5" r="2"/></svg>
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 -mt-8">
        <div class="grid lg:grid-cols-3 gap-6">

    <div class="grid lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tracking Number Card -->
                @if($order->tracking_number)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                                <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-semibold mb-1">เลขพัสดุ (ไปรษณีย์ไทย)</p>
                                <p class="text-2xl font-bold font-mono tracking-wider text-white">{{ $order->tracking_number }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 py-6 flex flex-col sm:flex-row gap-4">
                        <a href="{{ $thaiPostUrl }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-3 py-4 px-6 bg-gradient-to-r from-orange-500 to-red-500 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-red-600 transition-all shadow-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            ตรวจสอบสถานะที่ไปรษณีย์ไทย
                        </a>
                        <button onclick="navigator.clipboard.writeText('{{ $order->tracking_number }}').then(() => { this.querySelector('span').textContent = 'คัดลอกแล้ว!'; setTimeout(() => { this.querySelector('span').textContent = 'คัดลอกเลขพัสดุ'; }, 2000); })" class="flex items-center justify-center gap-3 py-4 px-6 bg-gray-50 text-gray-700 font-semibold rounded-xl hover:bg-gray-100 transition-all border border-gray-200">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            <span>คัดลอกเลขพัสดุ</span>
                        </button>
                    </div>
                </div>
                @else
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-12 text-center">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">ยังไม่มีเลขพัสดุ</h3>
                    <p class="text-gray-600 mb-6">เลขพัสดุจะแสดงเมื่อร้านค้าจัดส่งสินค้าแล้ว</p>
                    @if($order->status === 'processing')
                        <div class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold rounded-xl shadow-lg">
                            <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            กำลังจัดเตรียมสินค้า
                        </div>
                    @endif
                </div>
            @endif

                <!-- Status Timeline -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100">
                    <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center shadow-lg">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">ประวัติสถานะ</h2>
                                <p class="text-sm text-gray-500 mt-0.5">การเปลี่ยนแปลงสถานะออเดอร์</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        @if(count($history) > 0)
                            <div class="relative">
                                @foreach(array_reverse($history) as $idx => $entry)
                                    @php
                                        $isFirst = $idx === 0;
                                        $entryStatus = $entry['status'] ?? '';
                                        $entryNote = $entry['note'] ?? '';
                                        $entryTime = isset($entry['timestamp']) ? \Carbon\Carbon::parse($entry['timestamp']) : null;
                                        $colorClass = match($entryStatus) {
                                            'delivered' => 'bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-lg',
                                            'shipped' => 'bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-lg',
                                            'processing' => 'bg-gradient-to-br from-indigo-500 to-blue-600 text-white shadow-lg',
                                            'paid' => 'bg-gradient-to-br from-blue-500 to-cyan-600 text-white shadow-lg',
                                            'cancelled', 'expired' => 'bg-gradient-to-br from-red-500 to-pink-600 text-white shadow-lg',
                                            default => $isFirst ? 'bg-gradient-to-br from-gray-700 to-gray-800 text-white shadow-lg' : 'bg-gray-200 text-gray-500',
                                        };
                                    @endphp
                                    <div class="flex gap-6 {{ !$loop->last ? 'pb-8' : '' }} relative">
                                        {{-- Timeline line --}}
                                        @if(!$loop->last)
                                            <div class="absolute left-[20px] top-12 bottom-0 w-1 bg-gradient-to-b from-gray-200 to-transparent"></div>
                                        @endif
                                        {{-- Icon --}}
                                        <div class="w-12 h-12 rounded-full {{ $colorClass }} flex items-center justify-center shrink-0 z-10 {{ $isFirst ? 'ring-4 ring-white ring-opacity-50' : '' }}">
                                            @if($isFirst)
                                                <div class="w-6 h-6">{!! $statusIcons[$entryStatus] ?? '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/></svg>' !!}</div>
                                            @else
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                            @endif
                                        </div>
                                        {{-- Content --}}
                                        <div class="flex-1 min-w-0 pt-1">
                                            <div class="flex items-center gap-3 flex-wrap">
                                                <span class="text-base font-bold {{ $isFirst ? 'text-gray-900' : 'text-gray-600' }}">
                                                    {{ $statusLabels[$entryStatus] ?? $entryStatus }}
                                                </span>
                                                @if($isFirst)
                                                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-sm">ล่าสุด</span>
                                                @endif
                                            </div>
                                            @if($entryNote)
                                                <p class="text-gray-600 mt-2 bg-gray-50 rounded-lg px-4 py-2 text-sm">{{ $entryNote }}</p>
                                            @endif
                                            @if($entryTime)
                                                <p class="text-xs text-gray-400 mt-2">
                                                    <svg class="h-3 w-3 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y1="6"/><line x1="8" y1="2" x2="8" y1="6"/><line x1="3" y1="10" x2="21" y1="10"/></svg>
                                                    {{ $entryTime->locale('th')->translatedFormat('j F Y, H:i') }} น.
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                </div>
                                <p class="text-gray-400">ยังไม่มีประวัติสถานะ</p>
                            </div>
                        @endif
                    </div>
                </div>
        </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Order Items Summary -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center shadow-lg">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">สินค้าในคำสั่งซื้อ</h3>
                                <p class="text-sm text-gray-500 mt-0.5">{{ $order->items->count() }} รายการ</p>
                            </div>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                            <div class="px-6 py-4 flex gap-4 items-center hover:bg-gray-50 transition-colors">
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 shrink-0 border border-gray-200">
                                    @if($item->product_image)
                                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" />
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 line-clamp-2">{{ $item->product_name }}</p>
                                    @if(!empty($item->options))
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            @if(!empty($item->options['size']))
                                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-md">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                    {{ $item->options['size'] }}
                                                </span>
                                            @endif
                                            @if(!empty($item->options['color']))
                                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-md">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 01-4 4z"/></svg>
                                                    {{ $item->options['color'] }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="flex items-center justify-between mt-3">
                                        <span class="text-xs text-gray-500">จำนวน</span>
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-200 text-gray-700 text-sm font-bold rounded-lg">{{ $item->quantity }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-cyan-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">ที่อยู่จัดส่ง</h3>
                                <p class="text-sm text-gray-500 mt-0.5">ข้อมูลการจัดส่ง</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center shrink-0">
                                <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
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

                <!-- Thai Post Info -->
                <div class="bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl shadow-xl p-6 border border-orange-200">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center shrink-0">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white mb-1">จัดส่งโดย ไปรษณีย์ไทย</h3>
                            <p class="text-sm text-orange-100">ระยะเวลาจัดส่งโดยประมาณ 1-3 วันทำการ</p>
                            <div class="flex items-center gap-2 mt-3">
                                <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                <p class="text-xs text-orange-100">กำลังดำเนินการจัดส่ง</p>
                            </div>
                            <a href="https://www.thailandpost.co.th" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm text-white font-semibold rounded-xl hover:bg-white/30 transition-all border border-white/30">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                เว็บไซต์ไปรษณีย์ไทย
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Back Links -->
                <div class="space-y-3">
                    <a href="{{ route('account.orders.show', $order->id) }}" class="flex items-center justify-center gap-3 w-full px-6 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-all shadow-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        ดูรายละเอียดคำสั่งซื้อ
                    </a>
                    <a href="{{ route('account.orders') }}" class="flex items-center justify-center gap-3 w-full px-6 py-3 bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors rounded-xl">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                        กลับไปหน้าคำสั่งซื้อ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
