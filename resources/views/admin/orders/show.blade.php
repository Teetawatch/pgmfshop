@extends('admin.layout')
@section('title', 'รายละเอียดคำสั่งซื้อ #' . $order->order_number)

@php
    $colors = [
        'pending' => 'bg-gray-100 text-gray-700 border border-gray-200',
        'awaiting_payment' => 'bg-amber-50 text-amber-700 border border-amber-200',
        'paid' => 'bg-blue-50 text-blue-700 border border-blue-200',
        'processing' => 'bg-indigo-50 text-indigo-700 border border-indigo-200',
        'shipped' => 'bg-purple-50 text-purple-700 border border-purple-200',
        'delivered' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'cancelled' => 'bg-red-50 text-red-700 border border-red-200',
        'expired' => 'bg-gray-100 text-gray-700 border border-gray-200',
    ];
    $dotColors = [
        'pending' => 'bg-gray-500',
        'awaiting_payment' => 'bg-amber-500',
        'paid' => 'bg-blue-500',
        'processing' => 'bg-indigo-500',
        'shipped' => 'bg-purple-500',
        'delivered' => 'bg-emerald-500',
        'cancelled' => 'bg-red-500',
        'expired' => 'bg-gray-500',
    ];
    $labels = [
        'pending' => 'รอดำเนินการ',
        'awaiting_payment' => 'รอชำระ',
        'paid' => 'ชำระแล้ว',
        'processing' => 'กำลังจัดเตรียม',
        'shipped' => 'จัดส่งแล้ว',
        'delivered' => 'ส่งสำเร็จ',
        'cancelled' => 'ยกเลิก',
        'expired' => 'ไม่ชำระตามกำหนด',
    ];
@endphp

@section('content')
{{-- Page Header --}}
<div class="mb-8">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700 transition-colors">แดชบอร์ด</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('admin.orders.index') }}" class="hover:text-gray-700 transition-colors">คำสั่งซื้อ</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium">{{ $order->order_number }}</span>
    </div>
    
    <!-- Order Header -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">คำสั่งซื้อ #{{ $order->order_number }}</h1>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mt-1">
                        <p class="text-sm text-gray-500">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </p>
                        @if($order->user)
                        <p class="text-sm text-gray-500">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ $order->user->name }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold {{ $colors[$order->status] ?? '' }}">
                    <span class="w-2 h-2 rounded-full {{ $dotColors[$order->status] ?? 'bg-gray-400' }}"></span>
                    {{ $labels[$order->status] ?? $order->status }}
                </span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-teal-600 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        พิมพ์ใบเสร็จ
                    </a>
                    <a href="{{ route('admin.orders.shippingLabel', $order) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-purple-600 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.75 7.159l-.351.064"/></svg>
                        ใบปะหน้าพัสดุ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>

{{-- Payment Deadline Countdown (awaiting_payment only) --}}
@if($order->status === 'awaiting_payment' && $order->payment_deadline)
    <div x-data="{
        hours: '00', minutes: '00', seconds: '00', expired: false, interval: null,
        init() { this.tick(); this.interval = setInterval(() => this.tick(), 1000); },
        tick() {
            const diff = new Date('{{ $order->payment_deadline->toIso8601String() }}').getTime() - Date.now();
            if (diff <= 0) { this.hours='00'; this.minutes='00'; this.seconds='00'; this.expired=true; clearInterval(this.interval); return; }
            this.hours = String(Math.floor(diff/3600000)).padStart(2,'0');
            this.minutes = String(Math.floor((diff%3600000)/60000)).padStart(2,'0');
            this.seconds = String(Math.floor((diff%60000)/1000)).padStart(2,'0');
        },
        destroy() { if(this.interval) clearInterval(this.interval); }
    }" class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-amber-800">กำหนดชำระเงิน</p>
                    <p class="text-xs text-amber-600">ภายใน {{ $order->payment_deadline->format('d/m/Y H:i') }} น.</p>
                </div>
            </div>
            <div x-show="!expired" class="flex items-center gap-2">
                <div class="bg-white border border-amber-200 rounded-lg px-2.5 py-1.5 text-center min-w-[44px] shadow-sm">
                    <span class="text-lg font-bold font-mono text-amber-700" x-text="hours">00</span>
                    <p class="text-[9px] text-amber-500 -mt-0.5">ชม.</p>
                </div>
                <span class="text-amber-400 font-bold">:</span>
                <div class="bg-white border border-amber-200 rounded-lg px-2.5 py-1.5 text-center min-w-[44px] shadow-sm">
                    <span class="text-lg font-bold font-mono text-amber-700" x-text="minutes">00</span>
                    <p class="text-[9px] text-amber-500 -mt-0.5">นาที</p>
                </div>
                <span class="text-amber-400 font-bold">:</span>
                <div class="bg-white border border-amber-200 rounded-lg px-2.5 py-1.5 text-center min-w-[44px] shadow-sm">
                    <span class="text-lg font-bold font-mono text-amber-700" x-text="seconds">00</span>
                    <p class="text-[9px] text-amber-500 -mt-0.5">วินาที</p>
                </div>
            </div>
            <div x-show="expired" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 border border-red-200 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                <span class="text-xs font-semibold text-red-600">หมดเวลาชำระแล้ว</span>
            </div>
        </div>
    </div>
@endif

<div class="grid lg:grid-cols-3 gap-6">
    {{-- ==================== LEFT COLUMN ==================== --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Order Items --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">รายการสินค้า</h2>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $order->items->count() }} รายการ</p>
                    </div>
                </div>
            </div>

            {{-- Table Header --}}
            <div class="hidden sm:grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50/80 text-xs font-semibold text-gray-600 uppercase tracking-wide border-b border-gray-200">
                <div class="col-span-6">สินค้า</div>
                <div class="col-span-2 text-right">ราคาต่อหน่วย</div>
                <div class="col-span-2 text-center">จำนวน</div>
                <div class="col-span-2 text-right">รวม</div>
            </div>

            {{-- Items --}}
            <div class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                <div class="px-6 py-4 grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-12 sm:col-span-6 flex items-start gap-4">
                        @if($item->product_image)
                            <img src="{{ $item->product_image }}" alt="" class="w-16 h-16 rounded-xl object-cover bg-gray-100 border border-gray-200 shadow-sm shrink-0">
                        @else
                            <div class="w-16 h-16 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900 leading-tight">{{ $item->product_name }}</p>
                            @if(!empty($item->options))
                                <div class="flex flex-wrap gap-2 mt-1.5">
                                    @if(!empty($item->options['size']))
                                        <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-medium rounded-md">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            {{ $item->options['size'] }}
                                        </span>
                                    @endif
                                    @if(!empty($item->options['color']))
                                        <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-medium rounded-md">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 01-4 4z"/></svg>
                                            {{ $item->options['color'] }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="hidden sm:block col-span-2 text-right">
                        <p class="text-sm font-medium text-gray-700">฿{{ number_format($item->price, 0) }}</p>
                    </div>
                    <div class="hidden sm:block col-span-2 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg">
                            {{ $item->quantity }}
                        </span>
                    </div>
                    <div class="hidden sm:block col-span-2 text-right">
                        <p class="text-base font-bold text-gray-900">฿{{ number_format($item->total, 0) }}</p>
                    </div>
                    <div class="sm:hidden col-span-12 mt-3 pt-3 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">฿{{ number_format($item->price, 0) }} x {{ $item->quantity }}</span>
                            <span class="text-base font-bold text-gray-900">฿{{ number_format($item->total, 0) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="border-t border-gray-200 bg-gradient-to-br from-gray-50 to-white">
                <div class="px-6 py-5 space-y-3 max-w-sm ml-auto">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">ยอดสินค้า</span>
                        <span class="text-sm font-medium text-gray-900">฿{{ number_format($order->subtotal, 0) }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">ส่วนลด</span>
                        <span class="text-sm font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded">-฿{{ number_format($order->discount, 0) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">ค่าจัดส่ง</span>
                        <span class="text-sm font-medium text-gray-900">฿{{ number_format($order->shipping_cost, 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <span class="text-base font-bold text-gray-900">รวมทั้งหมด</span>
                        <span class="text-lg font-bold text-teal-600 bg-teal-50 px-3 py-1 rounded-lg">฿{{ number_format($order->total, 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Slip --}}
        @if($order->payment_slip)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-900">สลิปการชำระเงิน</h2>
                            <p class="text-sm text-gray-600 mt-0.5">หลักฐานการโอนเงิน</p>
                        </div>
                    </div>
                    @if($order->slip_verified)
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            ตรวจสอบแล้ว
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            รอตรวจสอบ
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-6">
                    {{-- Slip Image --}}
                    <div class="sm:w-56 shrink-0">
                        @php
                            $slipUrl = file_exists(public_path('uploads/' . $order->payment_slip))
                                ? asset('uploads/' . $order->payment_slip)
                                : asset('storage/' . $order->payment_slip);
                        @endphp
                        <a href="{{ $slipUrl }}" target="_blank" class="block group">
                            <img src="{{ $slipUrl }}" alt="สลิป" class="w-full rounded-lg border border-gray-200 group-hover:opacity-90 transition-opacity shadow-sm">
                        </a>
                        <p class="text-[10px] text-gray-400 mt-1.5 text-center">คลิกเพื่อดูขนาดเต็ม</p>
                    </div>

                    {{-- Transfer Info & Verification Data --}}
                    <div class="flex-1 space-y-4">
                        {{-- Transfer Details --}}
                        @if($order->transfer_date || $order->transfer_time || $order->transfer_amount)
                        <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <p class="text-[11px] font-medium text-blue-600 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                ข้อมูลการโอนเงิน
                            </p>
                            <div class="grid grid-cols-3 gap-3">
                                <div class="bg-white rounded-lg p-3 border border-blue-100 text-center">
                                    <p class="text-[10px] text-gray-500 mb-1">วันที่โอน</p>
                                    <p class="text-sm font-semibold text-gray-800">{{ $order->transfer_date ? $order->transfer_date->format('d/m/Y') : '-' }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-blue-100 text-center">
                                    <p class="text-[10px] text-gray-500 mb-1">เวลาที่โอน</p>
                                    <p class="text-sm font-semibold text-gray-800">{{ $order->transfer_time ? $order->transfer_time . ' น.' : '-' }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-blue-100 text-center">
                                    <p class="text-[10px] text-gray-500 mb-1">จำนวนเงิน</p>
                                    <p class="text-sm font-bold text-blue-700">{{ $order->transfer_amount ? '฿' . number_format($order->transfer_amount, 2) : '-' }}</p>
                                </div>
                            </div>
                            @if($order->transfer_amount && $order->total && (float)$order->transfer_amount != (float)$order->total)
                                <div class="mt-2.5 flex items-center gap-1.5 text-xs text-amber-700 bg-amber-50 rounded-lg px-3 py-2 border border-amber-200">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                                    <span>จำนวนเงินที่โอน (฿{{ number_format($order->transfer_amount, 2) }}) ไม่ตรงกับยอดคำสั่งซื้อ (฿{{ number_format($order->total, 2) }})</span>
                                </div>
                            @endif
                        </div>
                        @endif

                        @if($order->slip_verification_data)
                            @php $sv = $order->slip_verification_data; @endphp
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-medium text-gray-600">คะแนนตรวจสลิป</span>
                                    <span class="text-sm font-bold {{ ($sv['percentage'] ?? 0) >= 80 ? 'text-green-600' : (($sv['percentage'] ?? 0) >= 50 ? 'text-amber-600' : 'text-red-600') }}">{{ $sv['percentage'] ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all {{ ($sv['percentage'] ?? 0) >= 80 ? 'bg-green-500' : (($sv['percentage'] ?? 0) >= 50 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $sv['percentage'] ?? 0 }}%"></div>
                                </div>
                            </div>

                            @if(!empty($sv['checks']))
                                <div class="space-y-1.5">
                                    <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">ผลการตรวจสอบ</p>
                                    @foreach($sv['checks'] as $chk)
                                        <div class="flex items-start gap-2 text-xs">
                                            @if($chk['passed'])
                                                <span class="w-4 h-4 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0 mt-0.5">
                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                                </span>
                                            @else
                                                <span class="w-4 h-4 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 mt-0.5">
                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </span>
                                            @endif
                                            <span class="{{ $chk['passed'] ? 'text-gray-600' : 'text-red-600' }}">{{ $chk['detail'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if(!empty($sv['warnings']))
                                <div class="p-3 bg-amber-50 rounded-lg border border-amber-100 space-y-1">
                                    @foreach($sv['warnings'] as $warn)
                                        <div class="flex items-start gap-1.5 text-xs text-amber-700">
                                            <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                                            <span>{{ $warn }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif

                        {{-- Slip Actions --}}
                        @if(!$order->slip_verified && !in_array($order->status, ['cancelled', 'expired']))
                        <div class="flex gap-2 pt-2">
                            <form action="{{ route('admin.orders.verifySlip', $order) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit" onclick="return confirm('ยืนยันว่าสลิปถูกต้อง? สถานะจะเปลี่ยนเป็น ชำระแล้ว')"
                                    class="w-full py-2.5 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    ยืนยันสลิปถูกต้อง
                                </button>
                            </form>
                            <form action="{{ route('admin.orders.rejectSlip', $order) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit" onclick="return confirm('ปฏิเสธสลิป? สถานะจะเปลี่ยนเป็น ยกเลิก และคืนสต็อก')"
                                    class="w-full py-2.5 bg-white text-red-600 border border-red-200 rounded-lg text-sm font-medium hover:bg-red-50 transition-colors flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    ปฏิเสธสลิป
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Status History / Timeline --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">ประวัติสถานะ</h3>
                        <p class="text-sm text-gray-600 mt-0.5">การเปลี่ยนแปลงสถานะออเดอร์</p>
                    </div>
                </div>
            </div>
                <h2 class="text-sm font-semibold text-gray-800">ประวัติสถานะ</h2>
            </div>
            <div class="p-6">
                @if($order->status_history)
                    <div class="relative">
                        {{-- Timeline line --}}
                        <div class="absolute left-[7px] top-2 bottom-2 w-px bg-gray-200"></div>

                        <div class="space-y-5">
                            @foreach(array_reverse($order->status_history) as $idx => $h)
                            <div class="flex gap-4 relative">
                                {{-- Dot --}}
                                <div class="relative z-10 shrink-0">
                                    @if($idx === 0)
                                        <div class="w-[15px] h-[15px] rounded-full border-[3px] border-teal-500 bg-white"></div>
                                    @else
                                        <div class="w-[15px] h-[15px] rounded-full border-2 border-gray-300 bg-white"></div>
                                    @endif
                                </div>
                                {{-- Content --}}
                                <div class="-mt-0.5">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium {{ $idx === 0 ? 'text-gray-900' : 'text-gray-600' }}">{{ $labels[$h['status']] ?? $h['status'] }}</span>
                                        <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($h['timestamp'])->format('d/m/Y H:i') }}</span>
                                    </div>
                                    @if(!empty($h['note']))
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $h['note'] }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-gray-400">ยังไม่มีประวัติ</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="space-y-6">

        {{-- Update Status --}}
        @if(!in_array($order->status, ['cancelled', 'expired', 'delivered']))
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-cyan-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">อัปเดตสถานะ</h3>
                        <p class="text-sm text-gray-600 mt-0.5">เปลี่ยนแปลงสถานะออเดอร์</p>
                    </div>
                </div>
            </div>
            <div class="p-5">
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">เปลี่ยนสถานะ</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-colors bg-white">
                            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>ชำระแล้ว</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>กำลังจัดเตรียม</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>จัดส่งแล้ว</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>ส่งสำเร็จ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">เลข Tracking</label>
                        <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="TH1234567890"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">หมายเหตุ</label>
                        <input type="text" name="note" placeholder="หมายเหตุ (ถ้ามี)"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-colors">
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-teal-600 text-white rounded-lg text-sm font-semibold hover:bg-teal-700 transition-colors shadow-sm">
                        อัปเดตสถานะ
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Customer Info --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-sky-50 to-blue-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">ข้อมูลลูกค้า</h3>
                        <p class="text-sm text-gray-600 mt-0.5">รายละเอียดผู้สั่งซื้อ</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center text-lg font-bold text-gray-600 shadow-sm">
                        {{ mb_substr($order->user->name ?? '?', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-base font-semibold text-gray-900">{{ $order->user->name ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ $order->user->email ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Shipping Address --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-orange-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-800">ที่อยู่จัดส่ง</h3>
            </div>
            <div class="p-5">
                @if($order->shipping_address)
                    <div class="text-sm space-y-1.5">
                        <p class="font-medium text-gray-800">{{ $order->shipping_address['name'] ?? '' }}</p>
                        @if(!empty($order->shipping_address['phone']))
                        <p class="text-gray-500 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            {{ $order->shipping_address['phone'] }}
                        </p>
                        @endif
                        <p class="text-gray-500 leading-relaxed">{{ $order->shipping_address['address'] ?? '' }}</p>
                        <p class="text-gray-500">{{ $order->shipping_address['district'] ?? '' }} {{ $order->shipping_address['province'] ?? '' }} {{ $order->shipping_address['postalCode'] ?? '' }}</p>
                    </div>
                @else
                    <p class="text-sm text-gray-400">ไม่มีข้อมูล</p>
                @endif
            </div>
        </div>

        {{-- Payment & Shipping Details --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-800">การชำระ & จัดส่ง</h3>
            </div>
            <div class="p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">ช่องทางชำระ</span>
                    <span class="text-sm font-medium text-gray-800">{{ $order->payment_method === 'promptpay' ? 'PromptPay' : ($order->payment_method === 'bank_transfer' ? 'โอนธนาคาร' : ($order->payment_method ?? '-')) }}</span>
                </div>
                <div class="border-t border-gray-100"></div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">วิธีจัดส่ง</span>
                    <span class="text-sm font-medium text-gray-800">{{ $order->shipping_method ?? '-' }}</span>
                </div>
                @if($order->tracking_number)
                <div class="border-t border-gray-100"></div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Tracking</span>
                    <span class="text-sm font-mono font-semibold text-teal-600">{{ $order->tracking_number }}</span>
                </div>
                @endif
                @if($order->coupon_code)
                <div class="border-t border-gray-100"></div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">คูปอง</span>
                    <span class="text-sm font-medium text-gray-800 bg-gray-100 px-2 py-0.5 rounded">{{ $order->coupon_code }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Cancel Order --}}
        @if(!in_array($order->status, ['delivered', 'cancelled', 'expired']))
        <div class="bg-white rounded-xl border border-red-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-red-50 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-red-700">ยกเลิกคำสั่งซื้อ</h3>
            </div>
            <div class="p-5">
                <button type="button" onclick="document.getElementById('cancelSection').classList.toggle('hidden')"
                    class="w-full py-2.5 bg-red-50 text-red-600 border border-red-200 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors">
                    ยกเลิกคำสั่งซื้อนี้
                </button>
                <div id="cancelSection" class="hidden mt-4">
                    <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" class="space-y-3">
                        @csrf @method('PATCH')
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">เหตุผลในการยกเลิก</label>
                            <input type="text" name="cancel_reason" placeholder="เช่น ลูกค้าขอยกเลิก, สินค้าหมด"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-red-300/30 focus:border-red-400 transition-colors">
                        </div>
                        <p class="text-[11px] text-red-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            สต็อกสินค้าจะถูกคืนกลับอัตโนมัติ
                        </p>
                        <button type="submit" onclick="return confirm('ยืนยันยกเลิกคำสั่งซื้อ {{ $order->order_number }}?')"
                            class="w-full py-2.5 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition-colors shadow-sm">
                            ยืนยันยกเลิก
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
