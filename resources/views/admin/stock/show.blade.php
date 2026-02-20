@extends('admin.layout')
@section('title', 'สต็อก: ' . $product->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.stock.index') }}" class="text-sm text-gray-500 hover:text-teal-600">← กลับไปจัดการสต็อก</a>
</div>

<!-- Product Info -->
<div class="bg-white rounded-xl border p-5 mb-6">
    <div class="flex items-start gap-4">
        @if($product->images && count($product->images) > 0)
            <img src="{{ $product->images[0] }}" alt="" class="w-16 h-16 rounded-xl object-cover bg-gray-100">
        @endif
        <div class="flex-1">
            <h2 class="text-lg font-bold text-gray-800">{{ $product->name }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $product->category->name ?? '-' }} · ราคา ฿{{ number_format($product->price, 0) }}</p>
        </div>
        <div class="text-right">
            <p class="text-3xl font-bold {{ $product->stock === 0 ? 'text-red-600' : ($product->stock <= 10 ? 'text-orange-600' : 'text-gray-800') }}">
                {{ number_format($product->stock) }}
            </p>
            <p class="text-xs text-gray-500">สต็อกปัจจุบัน</p>
        </div>
    </div>

    <!-- Quick Adjust Form -->
    <div class="mt-4 pt-4 border-t">
        <form action="{{ route('admin.stock.adjust', $product) }}" method="POST" class="flex flex-wrap items-end gap-3">
            @csrf
            <div>
                <label class="block text-xs text-gray-500 mb-1">ประเภท</label>
                <select name="type" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    <option value="in">+ รับเข้า</option>
                    <option value="out">- จ่ายออก</option>
                    <option value="adjust">= ตั้งค่า</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">จำนวน</label>
                <input type="number" name="quantity" min="0" required placeholder="0"
                    class="w-28 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs text-gray-500 mb-1">เหตุผล</label>
                <input type="text" name="reason" required placeholder="เช่น รับของจากซัพพลายเออร์, นับได้ไม่ตรง..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
            </div>
            <button type="submit" class="px-5 py-2 bg-teal-600 text-white rounded-lg text-sm hover:bg-teal-700 font-medium">บันทึก</button>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    <div class="bg-white rounded-xl p-4 border">
        <p class="text-[10px] text-gray-500 uppercase">ขายแล้ว</p>
        <p class="text-xl font-bold text-gray-800 mt-1">{{ number_format($product->sold) }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 border">
        <p class="text-[10px] text-gray-500 uppercase">มูลค่าสต็อก</p>
        <p class="text-xl font-bold text-teal-700 mt-1">฿{{ number_format($product->stock * $product->price, 0) }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 border">
        <p class="text-[10px] text-gray-500 uppercase">รับเข้าทั้งหมด</p>
        <p class="text-xl font-bold text-green-700 mt-1">{{ number_format($product->stockMovements()->where('quantity', '>', 0)->sum('quantity')) }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 border">
        <p class="text-[10px] text-gray-500 uppercase">จ่ายออกทั้งหมด</p>
        <p class="text-xl font-bold text-red-700 mt-1">{{ number_format(abs($product->stockMovements()->where('quantity', '<', 0)->sum('quantity'))) }}</p>
    </div>
</div>

<!-- Variant Stock Breakdown -->
@if($product->isClothing() && $product->variants->isNotEmpty())
<div class="bg-white rounded-xl border overflow-hidden mb-6">
    <div class="px-5 py-4 border-b">
        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
            สต็อกแต่ละตัวเลือก
            <span class="text-xs font-normal text-gray-400">({{ $product->variants->count() }} ตัวเลือก)</span>
        </h3>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500">
            <tr>
                @if($product->variants->whereNotNull('size')->isNotEmpty())
                    <th class="px-5 py-2.5 text-left">ไซส์</th>
                @endif
                @if($product->variants->whereNotNull('color')->isNotEmpty())
                    <th class="px-5 py-2.5 text-left">สี</th>
                @endif
                <th class="px-5 py-2.5 text-center">สต็อก</th>
                <th class="px-5 py-2.5 text-center">สถานะ</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($product->variants->sortBy(['size', 'color']) as $variant)
            <tr class="hover:bg-gray-50">
                @if($product->variants->whereNotNull('size')->isNotEmpty())
                    <td class="px-5 py-2.5 font-medium text-gray-700">{{ $variant->size ?? '-' }}</td>
                @endif
                @if($product->variants->whereNotNull('color')->isNotEmpty())
                    <td class="px-5 py-2.5 text-gray-700">{{ $variant->color ?? '-' }}</td>
                @endif
                <td class="px-5 py-2.5 text-center font-bold {{ $variant->stock === 0 ? 'text-red-600' : ($variant->stock <= 5 ? 'text-orange-600' : 'text-gray-800') }}">
                    {{ number_format($variant->stock) }}
                </td>
                <td class="px-5 py-2.5 text-center">
                    @if($variant->stock === 0)
                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">หมด</span>
                    @elseif($variant->stock <= 5)
                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700">เหลือน้อย</span>
                    @else
                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700">ปกติ</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endif

<!-- Movement History -->
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="px-5 py-4 border-b">
        <h3 class="text-sm font-semibold text-gray-800">ประวัติการเคลื่อนไหวสต็อก</h3>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[800px]">
        <thead class="bg-gray-50 text-xs text-gray-500">
            <tr>
                <th class="px-5 py-3 text-left">วันที่</th>
                <th class="px-5 py-3 text-center">ประเภท</th>
                <th class="px-5 py-3 text-left">ตัวเลือก</th>
                <th class="px-5 py-3 text-center">จำนวน</th>
                <th class="px-5 py-3 text-center">ก่อน → หลัง</th>
                <th class="px-5 py-3 text-left">เหตุผล</th>
                <th class="px-5 py-3 text-left">อ้างอิง</th>
                <th class="px-5 py-3 text-left">โดย</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($movements as $mv)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-gray-600 text-xs">{{ $mv->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-5 py-3 text-center">
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
                <td class="px-5 py-3 text-xs text-gray-500">
                    @if($mv->variant)
                        {{ $mv->variant->getLabel() }}
                    @else
                        -
                    @endif
                </td>
                <td class="px-5 py-3 text-center font-bold {{ $mv->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $mv->quantity > 0 ? '+' : '' }}{{ number_format($mv->quantity) }}
                </td>
                <td class="px-5 py-3 text-center text-xs text-gray-500">
                    {{ number_format($mv->stock_before) }} → {{ number_format($mv->stock_after) }}
                </td>
                <td class="px-5 py-3 text-gray-600 text-xs max-w-[200px] truncate">{{ $mv->reason ?? '-' }}</td>
                <td class="px-5 py-3 text-xs">
                    <span class="text-gray-500">{{ $mv->getReferenceLabel() }}</span>
                    @if($mv->reference_id)
                        <span class="text-gray-400">{{ $mv->reference_id }}</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-xs text-gray-500">{{ $mv->user->name ?? 'ระบบ' }}</td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-5 py-8 text-center text-gray-400">ยังไม่มีประวัติ</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

<div class="mt-4">{{ $movements->links() }}</div>
@endsection
