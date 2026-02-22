@extends('admin.layout')
@section('title', 'ประวัติสต็อกทั้งหมด')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.stock.index') }}" class="text-sm text-gray-500 hover:text-teal-600">← กลับไปจัดการสต็อก</a>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl border p-4 mb-6">
    <form method="GET" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs text-gray-500 mb-1">ประเภท</label>
            <select name="type" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                <option value="">ทั้งหมด</option>
                <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>รับเข้า</option>
                <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>จ่ายออก</option>
                <option value="adjust" {{ request('type') === 'adjust' ? 'selected' : '' }}>ปรับสต็อก</option>
                <option value="return" {{ request('type') === 'return' ? 'selected' : '' }}>คืนสต็อก</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">ที่มา</label>
            <select name="reference_type" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                <option value="">ทั้งหมด</option>
                <option value="manual" {{ request('reference_type') === 'manual' ? 'selected' : '' }}>ปรับมือ</option>
                <option value="order" {{ request('reference_type') === 'order' ? 'selected' : '' }}>คำสั่งซื้อ</option>
                <option value="return" {{ request('reference_type') === 'return' ? 'selected' : '' }}>คืนสินค้า</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">จากวันที่</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">ถึงวันที่</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-700">กรอง</button>
        @if(request()->hasAny(['type', 'reference_type', 'date_from', 'date_to']))
            <a href="{{ route('admin.stock.history') }}" class="px-3 py-2 text-gray-500 text-sm hover:text-gray-700">✕ ล้าง</a>
        @endif
    </form>
</div>

<!-- History Table -->
<div class="bg-white rounded-xl border overflow-hidden">
<div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[800px]">
        <thead class="bg-gray-50 text-xs text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">วันที่</th>
                <th class="px-4 py-3 text-left">สินค้า</th>
                <th class="px-4 py-3 text-center">ประเภท</th>
                <th class="px-4 py-3 text-center">จำนวน</th>
                <th class="px-4 py-3 text-center">ก่อน → หลัง</th>
                <th class="px-4 py-3 text-left">เหตุผล</th>
                <th class="px-4 py-3 text-left">อ้างอิง</th>
                <th class="px-4 py-3 text-left">โดย</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($movements as $mv)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-600 text-xs whitespace-nowrap">{{ $mv->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-3">
                    @if($mv->product)
                        <a href="{{ route('admin.stock.show', $mv->product) }}" class="text-gray-800 hover:text-teal-600 font-medium text-xs truncate block max-w-[180px]">
                            {{ $mv->product->name }}
                        </a>
                    @else
                        <span class="text-gray-400 text-xs">ลบแล้ว</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center">
                    @php
                        $typeColors = [
                            'in' => 'bg-green-100 text-green-700',
                            'out' => 'bg-red-100 text-red-700',
                            'adjust' => 'bg-blue-100 text-blue-700',
                            'return' => 'bg-purple-100 text-purple-700',
                            'initial' => 'bg-gray-100 text-gray-700',
                        ];
                    @endphp
                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold {{ $typeColors[$mv->type] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $mv->getTypeLabel() }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center font-bold {{ $mv->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $mv->quantity > 0 ? '+' : '' }}{{ number_format($mv->quantity) }}
                </td>
                <td class="px-4 py-3 text-center text-xs text-gray-500">
                    {{ number_format($mv->stock_before) }} → {{ number_format($mv->stock_after) }}
                </td>
                <td class="px-4 py-3 text-gray-600 text-xs max-w-[180px] truncate">{{ $mv->reason ?? '-' }}</td>
                <td class="px-4 py-3 text-xs">
                    <span class="text-gray-500">{{ $mv->getReferenceLabel() }}</span>
                    @if($mv->reference_id)
                        <span class="text-gray-400 ml-1">{{ $mv->reference_id }}</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-gray-500">{{ $mv->user->name ?? 'ระบบ' }}</td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">ไม่พบประวัติ</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

<div class="mt-4">{{ $movements->withQueryString()->links() }}</div>
@endsection
