@extends('admin.layout')
@section('title', 'รายละเอียดคำสั่งซื้อ #' . $order->order_number)

@php
    $colors = [
        'pending' => 'bg-gray-50 text-gray-700 border border-gray-200',
        'awaiting_payment' => 'bg-amber-50 text-amber-700 border border-amber-200',
        'paid' => 'bg-green-50 text-green-700 border border-green-200',
        'processing' => 'bg-blue-50 text-blue-700 border border-blue-200',
        'shipped' => 'bg-purple-50 text-purple-700 border border-purple-200',
        'delivered' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'cancelled' => 'bg-red-50 text-red-700 border border-red-200',
        'expired' => 'bg-gray-50 text-gray-700 border border-gray-200',
    ];
    $dotColors = [
        'pending' => 'bg-gray-500',
        'awaiting_payment' => 'bg-amber-500',
        'paid' => 'bg-green-500',
        'processing' => 'bg-blue-500',
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
        <x-heroicon-o-chevron-right class="w-4 h-4" />
        <a href="{{ route('admin.orders.index') }}" class="hover:text-gray-700 transition-colors">คำสั่งซื้อ</a>
        <x-heroicon-o-chevron-right class="w-4 h-4" />
        <span class="text-gray-900 font-medium">{{ $order->order_number }}</span>
    </div>
    
    <!-- Order Header -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                    <x-heroicon-o-document-text class="w-6 h-6 text-gray-600" />
                </div>
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">คำสั่งซื้อ #{{ $order->order_number }}</h1>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6 mt-2">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <x-heroicon-o-calendar class="w-4 h-4" />
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </div>
                        @if($order->user)
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <x-heroicon-o-user class="w-4 h-4" />
                            {{ $order->user->name }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium {{ $colors[$order->status] ?? '' }}">
                    <span class="w-2 h-2 rounded-full {{ $dotColors[$order->status] ?? 'bg-gray-400' }}"></span>
                    {{ $labels[$order->status] ?? $order->status }}
                </span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors">
                        <x-heroicon-o-document class="w-4 h-4" />
                        พิมพ์ใบเสร็จ
                    </a>
                    <a href="{{ route('admin.orders.shippingLabel', $order) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors">
                        <x-heroicon-o-printer class="w-4 h-4" />
                        ใบปะหน้าพัสดุ
                    </a>
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
    }" class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white border border-gray-300 flex items-center justify-center shrink-0">
                    <x-heroicon-o-clock class="w-5 h-5 text-amber-600" />
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">กำหนดชำระเงิน</p>
                    <p class="text-xs text-gray-600">ภายใน {{ $order->payment_deadline->format('d/m/Y H:i') }} น.</p>
                </div>
            </div>
            <div x-show="!expired" class="flex items-center gap-3">
                <div class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-center min-w-[48px]">
                    <span class="text-lg font-bold font-mono text-gray-900" x-text="hours">00</span>
                    <p class="text-[10px] text-gray-500 -mt-0.5">ชม.</p>
                </div>
                <span class="text-gray-400 font-bold text-lg">:</span>
                <div class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-center min-w-[48px]">
                    <span class="text-lg font-bold font-mono text-gray-900" x-text="minutes">00</span>
                    <p class="text-[10px] text-gray-500 -mt-0.5">นาที</p>
                </div>
                <span class="text-gray-400 font-bold text-lg">:</span>
                <div class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-center min-w-[48px]">
                    <span class="text-lg font-bold font-mono text-gray-900" x-text="seconds">00</span>
                    <p class="text-[10px] text-gray-500 -mt-0.5">วินาที</p>
                </div>
            </div>
            <div x-show="expired" class="inline-flex items-center gap-2 px-3 py-2 bg-red-50 border border-red-200 rounded-lg">
                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                <span class="text-sm font-semibold text-red-600">หมดเวลาชำระแล้ว</span>
            </div>
        </div>
    </div>
@endif

<div class="grid lg:grid-cols-3 gap-6">
    {{-- ==================== LEFT COLUMN ==================== --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Order Items --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                        <x-heroicon-o-cube class="w-5 h-5 text-gray-600" />
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">รายการสินค้า</h2>
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
                                <x-heroicon-o-photo class="w-6 h-6 text-gray-300" />
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900 leading-tight">{{ $item->product_name }}</p>
                            @if(!empty($item->options))
                                <div class="flex flex-wrap gap-2 mt-1.5">
                                    @if(!empty($item->options['size']))
                                        <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-medium rounded-md">
                                            <x-heroicon-o-square-2-stack class="w-3 h-3 mr-1" />
                                            {{ $item->options['size'] }}
                                        </span>
                                    @endif
                                    @if(!empty($item->options['color']))
                                        <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-medium rounded-md">
                                            <x-heroicon-o-swatch class="w-3 h-3 mr-1" />
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
            <div class="border-t border-gray-200 bg-gray-50">
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
                    <div class="flex justify-between items-center pt-3 border-t border-gray-300">
                        <span class="text-base font-semibold text-gray-900">รวมทั้งหมด</span>
                        <span class="text-lg font-bold text-gray-900 bg-white px-3 py-1 rounded-lg border border-gray-300">฿{{ number_format($order->total, 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Slip --}}
        @if($order->payment_slip)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                            <x-heroicon-o-banknotes class="w-5 h-5 text-gray-600" />
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">สลิปการชำระเงิน</h2>
                            <p class="text-sm text-gray-500 mt-0.5">หลักฐานการโอนเงิน</p>
                        </div>
                    </div>
                    @if($order->slip_verified)
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-semibold bg-green-50 text-green-700 border border-green-200">
                            <x-heroicon-o-check-circle class="w-4 h-4" />
                            ตรวจสอบแล้ว
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            <x-heroicon-o-clock class="w-4 h-4" />
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
                                <x-heroicon-o-document-text class="w-3.5 h-3.5" />
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
                                    <x-heroicon-o-exclamation-triangle class="w-3.5 h-3.5 shrink-0" />
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
                                                    <x-heroicon-o-check class="w-2.5 h-2.5" />
                                                </span>
                                            @else
                                                <span class="w-4 h-4 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 mt-0.5">
                                                    <x-heroicon-o-x-mark class="w-2.5 h-2.5" />
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
                                            <x-heroicon-o-exclamation-triangle class="w-3.5 h-3.5 shrink-0 mt-0.5" />
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
                                    <x-heroicon-o-check-circle class="w-4 h-4" />
                                    ยืนยันสลิปถูกต้อง
                                </button>
                            </form>
                            <form action="{{ route('admin.orders.rejectSlip', $order) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit" onclick="return confirm('ปฏิเสธสลิป? สถานะจะเปลี่ยนเป็น ยกเลิก และคืนสต็อก')"
                                    class="w-full py-2.5 bg-white text-red-600 border border-red-200 rounded-lg text-sm font-medium hover:bg-red-50 transition-colors flex items-center justify-center gap-1.5">
                                    <x-heroicon-o-x-circle class="w-4 h-4" />
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
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                        <x-heroicon-o-clock class="w-5 h-5 text-gray-600" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">ประวัติสถานะ</h3>
                        <p class="text-sm text-gray-500 mt-0.5">การเปลี่ยนแปลงสถานะออเดอร์</p>
                    </div>
                </div>
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
                        <x-heroicon-o-clock class="w-8 h-8 text-gray-300 mx-auto mb-2" />
                        <p class="text-sm text-gray-400">ยังไม่มีประวัติ</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- ==================== RIGHT COLUMN ==================== --}}
    <div class="space-y-6">

        {{-- Update Status --}}
        @if(!in_array($order->status, ['cancelled', 'expired', 'delivered']))
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                        <x-heroicon-o-arrow-path class="w-5 h-5 text-gray-600" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">อัปเดตสถานะ</h3>
                        <p class="text-sm text-gray-500 mt-0.5">เปลี่ยนแปลงสถานะออเดอร์</p>
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
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                        <x-heroicon-o-user class="w-5 h-5 text-gray-600" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">ข้อมูลลูกค้า</h3>
                        <p class="text-sm text-gray-500 mt-0.5">รายละเอียดผู้สั่งซื้อ</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-lg font-semibold text-gray-600 border border-gray-300">
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
            <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                    <x-heroicon-o-map-pin class="w-5 h-5 text-gray-600" />
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">ที่อยู่จัดส่ง</h3>
                    <p class="text-sm text-gray-500 mt-0.5">ข้อมูลที่อยู่สำหรับจัดส่ง</p>
                </div>
            </div>
            <div class="p-5">
                @if($order->shipping_address)
                    <div class="text-sm space-y-1.5">
                        <p class="font-medium text-gray-800">{{ $order->shipping_address['name'] ?? '' }}</p>
                        @if(!empty($order->shipping_address['phone']))
                        <p class="text-gray-500 flex items-center gap-1.5">
                            <x-heroicon-o-phone class="w-3.5 h-3.5 text-gray-400" />
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
                    <x-heroicon-o-credit-card class="w-4 h-4 text-emerald-600" />
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
                    <x-heroicon-o-exclamation-triangle class="w-4 h-4 text-red-500" />
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
                            <x-heroicon-o-exclamation-circle class="w-3 h-3" />
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
