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
    $statusDots = [
        'pending'          => 'bg-yellow-400',
        'awaiting_payment' => 'bg-orange-400',
        'paid'             => 'bg-blue-400',
        'processing'       => 'bg-indigo-400',
        'shipped'          => 'bg-purple-400',
        'delivered'        => 'bg-green-500',
        'cancelled'        => 'bg-red-400',
        'expired'          => 'bg-gray-400',
    ];
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
                <span class="text-white font-medium">คำสั่งซื้อ</span>
            </div>
            {{-- Title --}}
            <div class="flex items-center gap-4">
                <div class="p-3 bg-white/10 rounded-xl">
                    <span class="material-icons-outlined text-3xl text-white">inventory_2</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">คำสั่งซื้อของฉัน</h1>
                    <p class="text-gray-400 text-sm mt-0.5">{{ $orders->count() }} รายการ</p>
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
                           class="flex items-center gap-3 px-4 py-3 rounded-lg bg-orange-50 text-[#ff6b00] font-medium transition-colors">
                            <span class="material-icons-outlined">inventory_2</span>
                            คำสั่งซื้อ
                        </a>
                        <a href="{{ route('account.addresses') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <span class="material-icons-outlined">location_on</span>
                            ที่อยู่จัดส่ง
                        </a>
                        <a href="{{ route('account.wishlist') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
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

            {{-- ===== ORDER LIST ===== --}}
            <div class="lg:col-span-3">

        @if($orders->count() === 0)
            {{-- Empty State --}}
            <div class="bg-white rounded-xl shadow-sm p-12 flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4 shadow-sm">
                    <span class="material-icons-outlined text-4xl text-gray-400">inventory_2</span>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">ยังไม่มีคำสั่งซื้อ</h2>
                <p class="text-gray-500 text-sm max-w-xs mb-6">เริ่มช้อปปิ้งและสั่งซื้อสินค้าที่คุณชื่นชอบได้เลย!</p>
                <a href="{{ route('products') }}"
                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#ff6b00] text-white rounded-full text-sm font-medium hover:bg-orange-600 transition-colors shadow-sm">
                    <span class="material-icons-outlined text-base">shopping_bag</span>
                    เลือกซื้อสินค้า
                </a>
            </div>

        @else
            <div class="space-y-5">
                @foreach($orders as $order)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                        {{-- Card Header --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-sm font-bold text-gray-900 tracking-wide">{{ $order->order_number }}</span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$order->status] ?? 'bg-gray-400' }}"></span>
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 text-right">
                                <span class="text-sm text-gray-400">{{ $order->created_at->locale('th')->translatedFormat('j M Y, H:i') }}</span>
                                {{-- Countdown for awaiting_payment --}}
                                @if($order->status === 'awaiting_payment' && $order->payment_deadline)
                                    <div x-data="paymentCountdown('{{ $order->payment_deadline->toIso8601String() }}')">
                                        <div x-show="!expired"
                                             class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 border border-amber-200 rounded-full">
                                            <span class="material-icons-outlined text-sm text-amber-500">schedule</span>
                                            <span class="text-xs font-mono font-semibold text-amber-700">
                                                <span x-text="hours">00</span>:<span x-text="minutes">00</span>:<span x-text="seconds">00</span>
                                            </span>
                                        </div>
                                        <div x-show="expired">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 border border-red-200 rounded-full text-xs font-medium text-red-600">หมดเวลาชำระ</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Items List --}}
                        <div class="px-6 py-4 space-y-3">
                            @foreach($order->items->take(3) as $item)
                                <div class="flex gap-3 items-center">
                                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-100 shrink-0 border border-gray-200">
                                        @if($item->product_image)
                                            <img src="{{ $item->product_image }}"
                                                 alt="{{ $item->product_name }}"
                                                 class="w-full h-full object-cover" />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <span class="material-icons-outlined">image_not_supported</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 line-clamp-1">{{ $item->product_name }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">× {{ $item->quantity }}</p>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900 shrink-0">฿{{ number_format($item->price * $item->quantity, 0) }}</p>
                                </div>
                            @endforeach
                            @if($order->items->count() > 3)
                                <p class="text-xs text-gray-400 pl-1">และอีก {{ $order->items->count() - 3 }} รายการ</p>
                            @endif
                        </div>

                        {{-- Card Footer --}}
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-4">
                                <div>
                                    <span class="text-xs text-gray-400">ยอดรวม</span>
                                    <span class="ml-2 text-base font-bold text-[#ff6b00]">฿{{ number_format($order->total, 0) }}</span>
                                </div>
                                @if($order->tracking_number)
                                    <div class="flex items-center gap-1.5 text-xs text-gray-500 bg-white border border-gray-200 rounded-lg px-2.5 py-1.5">
                                        <span class="material-icons-outlined text-sm text-gray-400">local_shipping</span>
                                        <span class="font-mono font-medium">{{ $order->tracking_number }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex gap-2 shrink-0">
                                @if($order->tracking_number)
                                    <a href="{{ route('account.orders.tracking', $order->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-2 text-sm bg-[#ff6b00] text-white rounded-lg hover:bg-orange-600 transition-colors font-medium shadow-sm">
                                        <span class="material-icons-outlined text-base">track_changes</span>
                                        ติดตามพัสดุ
                                    </a>
                                @endif
                                <a href="{{ route('account.orders.show', $order->id) }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-2 text-sm border border-gray-200 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                    ดูรายละเอียด
                                    <span class="material-icons-outlined text-base">chevron_right</span>
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

            </div>{{-- end lg:col-span-3 --}}
        </div>{{-- end grid --}}
    </main>
</div>
