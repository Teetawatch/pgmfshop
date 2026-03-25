@extends('admin.layout')

@section('title', 'จัดส่งพัสดุ')

@section('content')
<div x-data="dispatchManager()" x-init="init()">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">จัดส่งพัสดุ</h2>
            <p class="text-sm text-gray-500 mt-1">สแกน Barcode จากไปรษณีย์ไทยเพื่ออัปเดตเลข Tracking</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="printSelected()" :disabled="selectedIds.length === 0"
                class="flex items-center gap-2 px-4 py-2.5 bg-purple-600 text-white rounded-xl text-sm font-medium hover:bg-purple-700 disabled:opacity-40 disabled:cursor-not-allowed transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span x-text="selectedIds.length > 0 ? `พิมพ์ใบปะหน้า (${selectedIds.length})` : 'พิมพ์ใบปะหน้า'"></span>
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-soft border border-gray-100">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">รอจัดส่งทั้งหมด</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-soft border border-green-100">
            <p class="text-xs text-green-600 font-medium uppercase tracking-wider">มีเลข Tracking แล้ว</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['has_tracking'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-soft border border-orange-100">
            <p class="text-xs text-orange-600 font-medium uppercase tracking-wider">ยังไม่มีเลข Tracking</p>
            <p class="text-3xl font-bold text-orange-600 mt-1">{{ $stats['no_tracking'] }}</p>
        </div>
    </div>

    {{-- Scanner Mode Banner --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-4 mb-6 shadow-md">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.24M16.24 12l1.76-4m-4 0H8m8 0h2m-2 0V4m-4 0V3m0 1v1m0 4v1"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm">โหมดสแกน Barcode</p>
                    <p class="text-blue-100 text-xs mt-0.5">
                        <span x-show="!scanMode">กดเปิดใช้งาน เพื่อยิง barcode จากเครื่องสแกนได้เลย</span>
                        <span x-show="scanMode" x-cloak>
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                พร้อมรับการสแกน — วางเคอร์เซอร์ที่ช่อง Tracking แล้วยิง barcode ได้เลย
                            </span>
                        </span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <span class="text-white text-sm font-medium">Auto-print</span>
                    <div class="relative">
                        <input type="checkbox" x-model="autoPrint" class="sr-only">
                        <div class="w-10 h-6 rounded-full transition-colors" :class="autoPrint ? 'bg-green-400' : 'bg-white/30'"></div>
                        <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform" :class="autoPrint ? 'translate-x-4' : ''"></div>
                    </div>
                </label>
                <button @click="toggleScanMode()"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition-all"
                    :class="scanMode ? 'bg-green-400 text-green-900 shadow-inner' : 'bg-white text-blue-700 hover:bg-blue-50'">
                    <span x-show="!scanMode">เปิดโหมดสแกน</span>
                    <span x-show="scanMode" x-cloak>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 bg-green-700 rounded-full animate-pulse"></span>
                            กำลังสแกน...
                        </span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- Toast Notification --}}
    <div x-show="toast.show" x-cloak x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-2xl text-white text-sm font-medium max-w-sm"
         :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'">
        <span x-show="toast.type === 'success'" class="text-lg">✅</span>
        <span x-show="toast.type === 'error'" class="text-lg">❌</span>
        <span x-text="toast.message"></span>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-soft p-4 mb-4 border border-gray-100">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="ค้นหาเลขคำสั่งซื้อ หรือชื่อลูกค้า..."
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
            </div>
            <select name="tracking_filter" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary outline-none">
                <option value="">ทั้งหมด</option>
                <option value="none" {{ request('tracking_filter') === 'none' ? 'selected' : '' }}>ยังไม่มี Tracking</option>
                <option value="has" {{ request('tracking_filter') === 'has' ? 'selected' : '' }}>มี Tracking แล้ว</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-teal-700 transition-colors">
                ค้นหา
            </button>
        </form>
    </div>

    {{-- Select All Bar --}}
    <div class="bg-white rounded-xl shadow-soft p-3 mb-4 border border-gray-100 flex items-center justify-between">
        <label class="flex items-center gap-2 cursor-pointer select-none text-sm text-gray-600">
            <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()"
                class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
            <span>เลือกทั้งหมด</span>
            <span class="text-gray-400" x-show="selectedIds.length > 0" x-cloak>
                (<span x-text="selectedIds.length"></span> รายการ)
            </span>
        </label>
        <div class="text-xs text-gray-400">
            แสดง {{ $orders->count() }} จาก {{ $orders->total() }} รายการ
        </div>
    </div>

    {{-- Order List --}}
    <div class="space-y-3">
        @forelse($orders as $index => $order)
        @php
            $address = $order->shipping_address ?? [];
            $recipientName = $address['name'] ?? ($order->user->name ?? '-');
            $recipientPhone = $address['phone'] ?? '';
            $province = $address['province'] ?? '';
            $hasTracking = !empty($order->tracking_number);
        @endphp
        <div class="bg-white rounded-xl shadow-soft border transition-all duration-200"
             :class="savedOrders['{{ $order->id }}'] ? 'border-green-400 bg-green-50' : 'border-gray-100 hover:border-gray-200'"
             id="order-row-{{ $order->id }}">
            <div class="p-4">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4">

                    {{-- Checkbox --}}
                    <div class="flex items-center shrink-0">
                        <input type="checkbox"
                            :value="{{ $order->id }}"
                            x-model="selectedIds"
                            class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary"
                            id="chk-{{ $order->id }}">
                    </div>

                    {{-- Order Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="font-bold text-gray-800 text-sm">{{ $order->order_number }}</span>
                            @if($order->status === 'processing')
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">กำลังดำเนินการ</span>
                            @elseif($order->status === 'paid')
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-medium">ชำระแล้ว</span>
                            @endif
                            @if($hasTracking)
                                <span class="px-2 py-0.5 bg-teal-100 text-teal-700 rounded-full text-xs font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    มี Tracking
                                </span>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-0.5 text-xs text-gray-500">
                            <span class="font-medium text-gray-700">{{ $recipientName }}</span>
                            @if($recipientPhone)<span>{{ $recipientPhone }}</span>@endif
                            @if($province)<span>📍 {{ $province }}</span>@endif
                            <span>{{ $order->items->sum('quantity') }} ชิ้น</span>
                            <span>฿{{ number_format($order->total, 0) }}</span>
                            <span>{{ $order->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    {{-- Tracking Input --}}
                    <div class="flex items-center gap-2 lg:w-80 shrink-0">
                        <div class="relative flex-1">
                            <input
                                type="text"
                                id="tracking-{{ $order->id }}"
                                data-order-id="{{ $order->id }}"
                                data-index="{{ $index }}"
                                value="{{ $order->tracking_number }}"
                                placeholder="ยิง Barcode หรือพิมพ์เลข Tracking..."
                                @keydown.enter.prevent="handleEnter($event, {{ $order->id }})"
                                @focus="currentFocus = {{ $order->id }}"
                                style="text-transform: uppercase;"
                                class="w-full border rounded-lg px-3 py-2 text-sm font-mono transition-all outline-none
                                    {{ $hasTracking ? 'border-teal-300 bg-teal-50 focus:ring-2 focus:ring-teal-400' : 'border-gray-200 bg-white focus:ring-2 focus:ring-primary' }}"
                                :class="savedOrders['{{ $order->id }}'] ? 'border-green-400 bg-green-50' : ''">
                            <div x-show="loadingOrders['{{ $order->id }}']" x-cloak
                                 class="absolute right-2 top-1/2 -translate-y-1/2">
                                <svg class="w-4 h-4 text-primary animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 12 0 12 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                        <button @click="saveTracking({{ $order->id }})"
                            :disabled="loadingOrders['{{ $order->id }}']"
                            class="px-3 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-teal-700 disabled:opacity-50 transition-all whitespace-nowrap shrink-0">
                            บันทึก
                        </button>
                        @if($hasTracking)
                        <a href="{{ route('admin.orders.shippingLabel', $order) }}" target="_blank"
                            class="px-3 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition-all whitespace-nowrap shrink-0">
                            🖨️
                        </a>
                        @else
                        <button @click="openLabelForOrder({{ $order->id }})"
                            :disabled="!savedOrders['{{ $order->id }}']"
                            class="px-3 py-2 bg-gray-200 text-gray-500 rounded-lg text-sm font-medium disabled:opacity-40 disabled:cursor-not-allowed transition-all whitespace-nowrap shrink-0">
                            🖨️
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Success message per row --}}
                <div x-show="savedOrders['{{ $order->id }}']" x-cloak
                     class="mt-2 flex items-center gap-2 text-xs text-green-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>บันทึกสำเร็จ — ส่ง Email แจ้งลูกค้าแล้ว</span>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-soft border border-gray-100 p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <p class="text-gray-500 font-medium">ไม่มีคำสั่งซื้อที่รอจัดส่ง</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
function dispatchManager() {
    return {
        scanMode: false,
        autoPrint: false,
        selectedIds: [],
        selectAll: false,
        loadingOrders: {},
        savedOrders: {},
        labelUrls: {},
        currentFocus: null,
        toast: { show: false, message: '', type: 'success' },
        orderIds: @json($orders->pluck('id')->toArray()),
        csrfToken: document.querySelector('meta[name="csrf-token"]').content,

        init() {
            // Focus first empty tracking input on load
            this.$nextTick(() => {
                const first = document.querySelector('input[id^="tracking-"]:not([value])') ||
                              document.querySelector('input[id^="tracking-"]');
                if (first) first.focus();
            });
        },

        toggleScanMode() {
            this.scanMode = !this.scanMode;
            if (this.scanMode) {
                this.$nextTick(() => {
                    const first = document.querySelector('input[id^="tracking-"]:not([value])') ||
                                  document.querySelector('input[id^="tracking-"]');
                    if (first) first.focus();
                });
            }
        },

        handleEnter(event, orderId) {
            const val = event.target.value.trim();
            if (!val) return;
            this.saveTracking(orderId, event.target, true);
        },

        async saveTracking(orderId, inputEl = null, fromScanner = false) {
            if (!inputEl) {
                inputEl = document.getElementById(`tracking-${orderId}`);
            }
            const tracking = inputEl ? inputEl.value.trim() : '';
            if (!tracking) {
                this.showToast('กรุณาใส่เลข Tracking', 'error');
                return;
            }

            this.loadingOrders[orderId] = true;

            try {
                const resp = await fetch(`/admin/dispatch/${orderId}/tracking`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ tracking_number: tracking }),
                });

                const data = await resp.json();

                if (data.success) {
                    this.savedOrders[orderId] = true;
                    this.labelUrls[orderId] = data.label_url;
                    if (inputEl) {
                        inputEl.classList.add('border-green-400', 'bg-green-50');
                        inputEl.classList.remove('border-gray-200', 'border-red-300');
                    }
                    this.playBeep('success');
                    this.showToast(data.message, 'success');
                    if (this.autoPrint && data.label_url) {
                        setTimeout(() => window.open(data.label_url, '_blank'), 200);
                    }
                    if (fromScanner || this.scanMode) {
                        this.focusNext(orderId);
                    }
                } else {
                    if (inputEl) {
                        inputEl.classList.add('border-red-300', 'bg-red-50');
                        inputEl.classList.remove('border-gray-200', 'border-teal-300');
                    }
                    this.playBeep('error');
                    this.showToast(data.message, 'error');
                }
            } catch (err) {
                this.playBeep('error');
                this.showToast('เกิดข้อผิดพลาด กรุณาลองใหม่', 'error');
            } finally {
                this.loadingOrders[orderId] = false;
            }
        },

        focusNext(currentOrderId) {
            const idx = this.orderIds.indexOf(currentOrderId);
            if (idx === -1) return;
            // Find next order that doesn't have tracking yet
            for (let i = idx + 1; i < this.orderIds.length; i++) {
                const nextInput = document.getElementById(`tracking-${this.orderIds[i]}`);
                if (nextInput && !this.savedOrders[this.orderIds[i]]) {
                    this.$nextTick(() => nextInput.focus());
                    return;
                }
            }
            // If no next empty, focus first empty from top
            for (let i = 0; i < this.orderIds.length; i++) {
                const input = document.getElementById(`tracking-${this.orderIds[i]}`);
                if (input && !this.savedOrders[this.orderIds[i]] && input.value.trim() === '') {
                    this.$nextTick(() => input.focus());
                    return;
                }
            }
        },

        openLabelForOrder(orderId) {
            if (this.labelUrls[orderId]) {
                window.open(this.labelUrls[orderId], '_blank');
            }
        },

        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedIds = [...this.orderIds];
            } else {
                this.selectedIds = [];
            }
        },

        printSelected() {
            if (this.selectedIds.length === 0) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.dispatch.bulkLabels") }}';
            form.target = '_blank';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = this.csrfToken;
            form.appendChild(csrf);

            this.selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        },

        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => { this.toast.show = false; }, 3500);
        },

        playBeep(type = 'success') {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                if (type === 'success') {
                    osc.frequency.setValueAtTime(880, ctx.currentTime);
                    osc.frequency.setValueAtTime(1100, ctx.currentTime + 0.08);
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.25);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.25);
                } else {
                    osc.frequency.setValueAtTime(300, ctx.currentTime);
                    gain.gain.setValueAtTime(0.4, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.3);
                }
            } catch (e) {}
        },
    };
}
</script>
@endsection
