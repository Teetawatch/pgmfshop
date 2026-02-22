@extends('admin.layout')
@section('title', 'ปรับสต็อกหลายรายการ')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.stock.index') }}" class="text-sm text-gray-500 hover:text-teal-600">← กลับไปจัดการสต็อก</a>
</div>

<!-- Search Filter -->
<div class="bg-white rounded-xl border p-4 mb-6">
    <form method="GET" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs text-gray-500 mb-1">ค้นหา</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาสินค้า..."
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-full sm:w-56 focus:ring-2 focus:ring-teal-500 outline-none">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">หมวดหมู่</label>
            <select name="category" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                <option value="">ทั้งหมด</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-700">กรอง</button>
    </form>
</div>

<!-- Bulk Form -->
<form action="{{ route('admin.stock.bulk.update') }}" method="POST" id="bulkForm">
    @csrf

    <div class="bg-white rounded-xl border p-4 mb-4">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[250px]">
                <label class="block text-xs text-gray-500 mb-1">เหตุผลการปรับ <span class="text-red-500">*</span></label>
                <input type="text" name="bulk_reason" required placeholder="เช่น รับสินค้าล็อตใหม่, ตรวจนับสต็อกประจำเดือน..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none" value="{{ old('bulk_reason') }}">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500" id="selectedCount">เลือก 0 รายการ</span>
                <button type="submit" class="px-5 py-2 bg-teal-600 text-white rounded-lg text-sm hover:bg-teal-700 font-medium">
                    💾 บันทึกทั้งหมด
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[600px]">
            <thead class="bg-gray-50 text-xs text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left w-8">
                        <input type="checkbox" id="checkAll" class="rounded border-gray-300">
                    </th>
                    <th class="px-4 py-3 text-left">สินค้า</th>
                    <th class="px-4 py-3 text-center">สต็อกปัจจุบัน</th>
                    <th class="px-4 py-3 text-center">ประเภท</th>
                    <th class="px-4 py-3 text-center">จำนวน</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($products as $i => $product)
                <tr class="hover:bg-gray-50 bulk-row" data-id="{{ $product->id }}">
                    <td class="px-4 py-3">
                        <input type="checkbox" class="row-check rounded border-gray-300" data-index="{{ $i }}">
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ $product->images[0] }}" alt="" class="w-8 h-8 rounded-lg object-cover bg-gray-100">
                            @else
                                <div class="w-8 h-8 rounded-lg bg-gray-100"></div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-800 text-xs">{{ $product->name }}</p>
                                <p class="text-[10px] text-gray-400">{{ $product->category->name ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-bold {{ $product->stock === 0 ? 'text-red-600' : ($product->stock <= 10 ? 'text-orange-600' : 'text-gray-800') }}">
                            {{ number_format($product->stock) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <select class="bulk-type px-2 py-1 border border-gray-200 rounded text-xs focus:ring-1 focus:ring-teal-400 outline-none" data-index="{{ $i }}" disabled>
                            <option value="in">+ เข้า</option>
                            <option value="out">- ออก</option>
                            <option value="adjust">= ตั้ง</option>
                        </select>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <input type="number" class="bulk-qty w-20 px-2 py-1 border border-gray-200 rounded text-xs text-center focus:ring-1 focus:ring-teal-400 outline-none" data-index="{{ $i }}" min="0" placeholder="0" disabled>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">ไม่พบสินค้า</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>

    <!-- Hidden inputs container -->
    <div id="hiddenInputs"></div>
</form>

<div class="mt-4">{{ $products->withQueryString()->links() }}</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('checkAll');
    const rowChecks = document.querySelectorAll('.row-check');
    const countEl = document.getElementById('selectedCount');
    const hiddenContainer = document.getElementById('hiddenInputs');

    function updateCount() {
        const checked = document.querySelectorAll('.row-check:checked').length;
        countEl.textContent = 'เลือก ' + checked + ' รายการ';
    }

    function toggleRow(checkbox) {
        const i = checkbox.dataset.index;
        const row = checkbox.closest('tr');
        const typeEl = row.querySelector('.bulk-type');
        const qtyEl = row.querySelector('.bulk-qty');

        if (checkbox.checked) {
            typeEl.disabled = false;
            qtyEl.disabled = false;
            row.classList.add('bg-teal-50/50');
        } else {
            typeEl.disabled = true;
            qtyEl.disabled = true;
            qtyEl.value = '';
            row.classList.remove('bg-teal-50/50');
        }
        updateCount();
    }

    checkAll.addEventListener('change', function() {
        rowChecks.forEach(cb => {
            cb.checked = checkAll.checked;
            toggleRow(cb);
        });
    });

    rowChecks.forEach(cb => cb.addEventListener('change', () => toggleRow(cb)));

    document.getElementById('bulkForm').addEventListener('submit', function(e) {
        hiddenContainer.innerHTML = '';
        let count = 0;

        document.querySelectorAll('.row-check:checked').forEach(cb => {
            const row = cb.closest('tr');
            const productId = row.dataset.id;
            const type = row.querySelector('.bulk-type').value;
            const qty = row.querySelector('.bulk-qty').value;

            if (!qty || qty == 0) return;

            const prefix = 'adjustments[' + count + ']';
            hiddenContainer.innerHTML += '<input type="hidden" name="' + prefix + '[product_id]" value="' + productId + '">';
            hiddenContainer.innerHTML += '<input type="hidden" name="' + prefix + '[type]" value="' + type + '">';
            hiddenContainer.innerHTML += '<input type="hidden" name="' + prefix + '[quantity]" value="' + qty + '">';
            count++;
        });

        if (count === 0) {
            e.preventDefault();
            alert('กรุณาเลือกสินค้าและกรอกจำนวนอย่างน้อย 1 รายการ');
        }
    });
});
</script>
@endsection
