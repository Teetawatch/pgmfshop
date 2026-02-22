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
@endphp

<div class="min-h-screen bg-gray-50">
    <!-- Hero Header -->
    <div class="bg-[#FF6512]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center gap-2 text-sm text-white/70 mb-4">
                <a href="{{ route('account') }}" class="hover:text-white transition-colors">บัญชีของฉัน</a>
                <x-heroicon-o-chevron-right class="h-3 w-3" />
                <span class="text-white font-medium">คำสั่งซื้อ</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <x-heroicon-o-cube class="h-6 w-6 text-white" />
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">คำสั่งซื้อของฉัน</h1>
                    <p class="text-white/70 text-sm">{{ $orders->count() }} รายการ</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if($orders->count() === 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 text-center py-16">
                <x-heroicon-o-cube class="h-12 w-12 mx-auto text-gray-300 mb-3" />
                <h2 class="font-bold mb-1">ยังไม่มีคำสั่งซื้อ</h2>
                <p class="text-sm text-gray-500 mb-5">เริ่มช้อปปิ้งเลย!</p>
                <a href="{{ route('products') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    เลือกซื้อสินค้า
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        {{-- Header --}}
                        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-sm font-bold text-gray-900">{{ $order->order_number }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-50 text-gray-700' }}">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm text-gray-500">{{ $order->created_at->locale('th')->translatedFormat('j F Y, H:i') }}</span>
                                @if($order->status === 'awaiting_payment' && $order->payment_deadline)
                                    <div x-data="paymentCountdown('{{ $order->payment_deadline->toIso8601String() }}')" class="mt-1">
                                        <div x-show="!expired" class="inline-flex items-center gap-1.5 px-2 py-1 bg-amber-50 border border-amber-200 rounded-full">
                                            <x-heroicon-o-clock class="h-3 w-3 text-amber-500" />
                                            <span class="text-xs font-medium text-amber-700 font-mono"><span x-text="hours">00</span>:<span x-text="minutes">00</span>:<span x-text="seconds">00</span></span>
                                        </div>
                                        <div x-show="expired">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-50 border border-red-200 rounded-full text-xs font-medium text-red-600">หมดเวลาชำระ</span>
                                        </div>
                                    </div>
                                @endif
                                @if($order->status === 'expired')
                                    <p class="text-xs text-gray-500 mt-0.5">ไม่ชำระเงินตามเวลาที่กำหนด</p>
                                @endif
                            </div>
                        </div>

                        {{-- Items --}}
                        <div class="px-6 py-4 space-y-3">
                            @foreach($order->items->take(3) as $item)
                                <div class="flex gap-3 items-center">
                                    <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-100 shrink-0 border border-gray-200">
                                        @if($item->product_image)
                                            <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <x-heroicon-o-cube class="h-5 w-5" />
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium line-clamp-1">{{ $item->product_name }}</p>
                                        <p class="text-xs text-gray-500">x{{ $item->quantity }}</p>
                                    </div>
                                    <p class="text-sm font-semibold shrink-0">฿{{ number_format($item->price * $item->quantity, 0) }}</p>
                                </div>
                            @endforeach
                            @if($order->items->count() > 3)
                                <p class="text-xs text-gray-400">และอีก {{ $order->items->count() - 3 }} รายการ</p>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <span class="text-sm text-gray-500">รวม </span>
                                <span class="text-base font-bold text-gray-900">฿{{ number_format($order->total, 0) }}</span>
                                @if($order->tracking_number)
                                    <span class="ml-3 text-xs text-gray-500">เลขพัสดุ: <span class="font-mono font-medium">{{ $order->tracking_number }}</span></span>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                @if($order->tracking_number)
                                    <a href="{{ route('account.orders.tracking', $order->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm border border-blue-200 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors font-medium">
                                        <x-heroicon-o-truck class="h-3.5 w-3.5" />
                                        ติดตามพัสดุ
                                    </a>
                                @endif
                                <a href="{{ route('account.orders.show', $order->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm border border-gray-200 rounded-lg hover:bg-white transition-colors font-medium">
                                    ดูรายละเอียด
                                    <x-heroicon-o-chevron-right class="h-3.5 w-3.5" />
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
