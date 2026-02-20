@extends('admin.layout')
@section('title', 'จัดการค่าจัดส่ง')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">จัดการค่าจัดส่ง</h2>
            <p class="text-sm text-gray-500 mt-1">ตั้งค่าอัตราค่าจัดส่งตามจำนวนสินค้า (ไปรษณีย์ไทย)</p>
        </div>
    </div>

    {{-- Carrier Info --}}
    <div class="bg-white rounded-lg border p-5 mb-6">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                <x-heroicon-o-envelope class="w-5 h-5 text-red-600" />
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">ไปรษณีย์ไทย</h3>
                <p class="text-xs text-gray-500">ผู้ให้บริการจัดส่งหลัก - ทุกพื้นที่ทั่วประเทศ</p>
            </div>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-700">
            <div class="flex items-start gap-2">
                <x-heroicon-o-information-circle class="w-4 h-4 mt-0.5 shrink-0" />
                <p>ระบบจะคำนวณค่าส่งอัตโนมัติตามจำนวนสินค้าในตะกร้า โดยใช้เงื่อนไขที่ตรงกับจำนวนสินค้ามากที่สุด</p>
            </div>
        </div>
    </div>

    {{-- Shipping Rates Form --}}
    <form action="{{ route('admin.shipping.update') }}" method="POST" id="shippingForm">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg border overflow-hidden">
            <div class="p-4 border-b bg-gray-50 flex items-center justify-between">
                <h3 class="font-semibold text-gray-700 text-sm">เงื่อนไขค่าจัดส่ง</h3>
                <button type="button" onclick="addRate()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary text-white text-xs font-medium rounded-lg hover:opacity-90 transition">
                    <x-heroicon-o-plus class="w-3.5 h-3.5" />
                    เพิ่มเงื่อนไข
                </button>
            </div>

            <div id="ratesContainer" class="divide-y">
                @forelse($rates as $index => $rate)
                    <div class="rate-row p-4 hover:bg-gray-50 transition" data-index="{{ $index }}">
                        <input type="hidden" name="rates[{{ $index }}][id]" value="{{ $rate->id }}">
                        <div class="grid grid-cols-12 gap-4 items-end">
                            {{-- Name --}}
                            <div class="col-span-12 sm:col-span-3">
                                <label class="block text-xs font-medium text-gray-500 mb-1">ชื่อเงื่อนไข</label>
                                <input type="text" name="rates[{{ $index }}][name]" value="{{ $rate->name }}" required
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" />
                            </div>
                            {{-- Min Quantity --}}
                            <div class="col-span-6 sm:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1">จำนวนขั้นต่ำ (เล่ม)</label>
                                <input type="number" name="rates[{{ $index }}][min_quantity]" value="{{ $rate->min_quantity }}" min="1" required
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" />
                            </div>
                            {{-- Max Quantity --}}
                            <div class="col-span-6 sm:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1">จำนวนสูงสุด (เล่ม)</label>
                                <input type="number" name="rates[{{ $index }}][max_quantity]" value="{{ $rate->max_quantity }}" min="1" placeholder="ไม่จำกัด"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" />
                            </div>
                            {{-- Price --}}
                            <div class="col-span-6 sm:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1">ค่าจัดส่ง (บาท)</label>
                                <input type="number" name="rates[{{ $index }}][price]" value="{{ intval($rate->price) }}" min="0" step="1" required
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary font-semibold" />
                            </div>
                            {{-- Active --}}
                            <div class="col-span-3 sm:col-span-1 flex items-center justify-center">
                                <label class="flex items-center gap-1.5 cursor-pointer">
                                    <input type="checkbox" name="rates[{{ $index }}][is_active]" {{ $rate->is_active ? 'checked' : '' }}
                                        class="w-4 h-4 accent-primary rounded" />
                                    <span class="text-xs text-gray-500">เปิด</span>
                                </label>
                            </div>
                            {{-- Delete --}}
                            <div class="col-span-3 sm:col-span-2 flex items-center justify-end">
                                <button type="button" onclick="removeRate(this)" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <x-heroicon-o-trash class="w-3.5 h-3.5" />
                                    ลบ
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div id="emptyState" class="p-8 text-center text-gray-400">
                        <x-heroicon-o-cube class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                        <p class="text-sm">ยังไม่มีเงื่อนไขค่าจัดส่ง</p>
                        <p class="text-xs mt-1">คลิก "เพิ่มเงื่อนไข" เพื่อเริ่มต้น</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Preview --}}
        <div class="bg-white rounded-lg border p-5 mt-6">
            <h3 class="font-semibold text-gray-700 text-sm mb-3">ตัวอย่างการคำนวณ</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" id="previewCards">
                @foreach([1, 2, 3, 4, 5, 6, 10] as $qty)
                    @php $cost = \App\Models\ShippingRate::getCostForQuantity($qty); @endphp
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-500">{{ $qty }} เล่ม</p>
                        <p class="text-lg font-bold text-primary">฿{{ number_format($cost, 0) }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-medium rounded-lg hover:opacity-90 transition">
                <x-heroicon-o-check class="w-4 h-4" />
                บันทึกค่าจัดส่ง
            </button>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 text-sm text-gray-600 hover:text-gray-800 transition">ยกเลิก</a>
        </div>
    </form>
</div>

<script>
let rateIndex = {{ $rates->count() }};

function addRate() {
    const emptyState = document.getElementById('emptyState');
    if (emptyState) emptyState.remove();

    const container = document.getElementById('ratesContainer');
    const html = `
        <div class="rate-row p-4 hover:bg-gray-50 transition" data-index="${rateIndex}">
            <input type="hidden" name="rates[${rateIndex}][id]" value="">
            <div class="grid grid-cols-12 gap-4 items-end">
                <div class="col-span-12 sm:col-span-3">
                    <label class="block text-xs font-medium text-gray-500 mb-1">ชื่อเงื่อนไข</label>
                    <input type="text" name="rates[${rateIndex}][name]" value="" required
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" placeholder="เช่น ค่าส่งพิเศษ" />
                </div>
                <div class="col-span-6 sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 mb-1">จำนวนขั้นต่ำ (เล่ม)</label>
                    <input type="number" name="rates[${rateIndex}][min_quantity]" value="1" min="1" required
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" />
                </div>
                <div class="col-span-6 sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 mb-1">จำนวนสูงสุด (เล่ม)</label>
                    <input type="number" name="rates[${rateIndex}][max_quantity]" value="" min="1" placeholder="ไม่จำกัด"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" />
                </div>
                <div class="col-span-6 sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 mb-1">ค่าจัดส่ง (บาท)</label>
                    <input type="number" name="rates[${rateIndex}][price]" value="0" min="0" step="1" required
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary font-semibold" />
                </div>
                <div class="col-span-3 sm:col-span-1 flex items-center justify-center">
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" name="rates[${rateIndex}][is_active]" checked
                            class="w-4 h-4 accent-primary rounded" />
                        <span class="text-xs text-gray-500">เปิด</span>
                    </label>
                </div>
                <div class="col-span-3 sm:col-span-2 flex items-center justify-end">
                    <button type="button" onclick="removeRate(this)" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs text-red-600 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        ลบ
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    rateIndex++;
}

function removeRate(btn) {
    const row = btn.closest('.rate-row');
    if (row) {
        row.remove();
        reindexRates();
    }
}

function reindexRates() {
    const rows = document.querySelectorAll('.rate-row');
    rows.forEach((row, i) => {
        row.dataset.index = i;
        row.querySelectorAll('[name]').forEach(input => {
            input.name = input.name.replace(/rates\[\d+\]/, `rates[${i}]`);
        });
    });
    rateIndex = rows.length;
}
</script>
@endsection
