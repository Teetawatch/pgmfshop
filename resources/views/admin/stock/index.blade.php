@extends('admin.layout')
@section('title', 'จัดการสต็อกสินค้า')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
    <div class="bg-white rounded-xl p-4 border">
        <p class="text-[10px] text-gray-500 uppercase tracking-wide">สินค้าทั้งหมด</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['total']) }}</p>
    </div>
    <a href="?status=out" class="bg-white rounded-xl p-4 border hover:border-red-300 transition {{ request('status') === 'out' ? 'ring-2 ring-red-400' : '' }}">
        <p class="text-[10px] text-gray-500 uppercase tracking-wide">หมดสต็อก</p>
        <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($stats['out_of_stock']) }}</p>
    </a>
    <a href="?status=low" class="bg-white rounded-xl p-4 border hover:border-orange-300 transition {{ request('status') === 'low' ? 'ring-2 ring-orange-400' : '' }}">
        <p class="text-[10px] text-gray-500 uppercase tracking-wide">วิกฤต (≤10)</p>
        <p class="text-2xl font-bold text-orange-600 mt-1">{{ number_format($stats['low_stock']) }}</p>
    </a>
    <a href="?status=warning" class="bg-white rounded-xl p-4 border hover:border-yellow-300 transition {{ request('status') === 'warning' ? 'ring-2 ring-yellow-400' : '' }}">
        <p class="text-[10px] text-gray-500 uppercase tracking-wide">เตือน (≤30)</p>
        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ number_format($stats['warning_stock']) }}</p>
    </a>
    <div class="bg-white rounded-xl p-4 border">
        <p class="text-[10px] text-gray-500 uppercase tracking-wide">มูลค่าสต็อก</p>
        <p class="text-xl font-bold text-teal-700 mt-1">฿{{ number_format($stats['total_value'], 0) }}</p>
    </div>
</div>

<!-- Toolbar -->
<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <form method="GET" class="flex flex-wrap items-center gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาสินค้า..."
            class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-full sm:w-56 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
        <select name="category" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
            <option value="">ทุกหมวดหมู่</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
            <option value="">ทุกสถานะ</option>
            <option value="out" {{ request('status') === 'out' ? 'selected' : '' }}>หมดสต็อก</option>
            <option value="low" {{ request('status') === 'low' ? 'selected' : '' }}>วิกฤต (≤10)</option>
            <option value="warning" {{ request('status') === 'warning' ? 'selected' : '' }}>เตือน (≤30)</option>
            <option value="ok" {{ request('status') === 'ok' ? 'selected' : '' }}>ปกติ (>30)</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-700">ค้นหา</button>
        @if(request()->hasAny(['search', 'category', 'status']))
            <a href="{{ route('admin.stock.index') }}" class="px-3 py-2 text-gray-500 text-sm hover:text-gray-700">✕ ล้าง</a>
        @endif
    </form>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.stock.history') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">
             ประวัติทั้งหมด
        </a>
        <a href="{{ route('admin.stock.bulk') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
             ปรับสต็อกหลายรายการ
        </a>
        <a href="{{ route('admin.stock.export') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
             Export CSV
        </a>
    </div>
</div>

<!-- Products Table -->
<div class="bg-white rounded-xl border overflow-hidden">
<div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[800px]">
        <thead class="bg-gray-50 text-xs text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">สินค้า</th>
                <th class="px-4 py-3 text-left">หมวดหมู่</th>
                <th class="px-4 py-3 text-right">ราคา</th>
                <th class="px-4 py-3 text-center">
                    <a href="?{{ http_build_query(array_merge(request()->except(['sort','dir']), ['sort' => 'stock', 'dir' => request('sort') === 'stock' && request('dir') === 'asc' ? 'desc' : 'asc'])) }}"
                       class="hover:text-teal-600">
                        สต็อก {!! request('sort','stock') === 'stock' ? (request('dir') === 'desc' ? '↓' : '↑') : '' !!}
                    </a>
                </th>
                <th class="px-4 py-3 text-center">
                    <a href="?{{ http_build_query(array_merge(request()->except(['sort','dir']), ['sort' => 'sold', 'dir' => request('sort') === 'sold' && request('dir') === 'desc' ? 'asc' : 'desc'])) }}"
                       class="hover:text-teal-600">
                        ขายแล้ว {!! request('sort') === 'sold' ? (request('dir') === 'desc' ? '↓' : '↑') : '' !!}
                    </a>
                </th>
                <th class="px-4 py-3 text-center">สถานะ</th>
                <th class="px-4 py-3 text-center">ปรับสต็อก</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50 {{ $product->stock === 0 ? 'bg-red-50/50' : ($product->stock <= 10 ? 'bg-orange-50/50' : '') }}" id="row-{{ $product->id }}">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ $product->images[0] }}" alt="" class="w-9 h-9 rounded-lg object-cover bg-gray-100">
                        @else
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-[10px]">N/A</div>
                        @endif
                        <div class="min-w-0">
                            <a href="{{ route('admin.stock.show', $product) }}" class="font-medium text-gray-800 hover:text-teal-600 truncate block max-w-[200px]">{{ $product->name }}</a>
                            <p class="text-[10px] text-gray-400">ID: {{ $product->id }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-600 text-xs">{{ $product->category->name ?? '-' }}</td>
                <td class="px-4 py-3 text-right font-medium">฿{{ number_format($product->price, 0) }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="text-lg font-bold {{ $product->stock === 0 ? 'text-red-600' : ($product->stock <= 10 ? 'text-orange-600' : ($product->stock <= 30 ? 'text-yellow-600' : 'text-gray-800')) }}">
                        {{ number_format($product->stock) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center text-gray-600">{{ number_format($product->sold) }}</td>
                <td class="px-4 py-3 text-center">
                    @if($product->stock === 0)
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-700">หมด</span>
                    @elseif($product->stock <= 10)
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700">วิกฤต</span>
                    @elseif($product->stock <= 30)
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-700">เตือน</span>
                    @else
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700">ปกติ</span>
                    @endif
                </td>
                <td class="px-4 py-2 text-center">
                    <form action="{{ route('admin.stock.adjust', $product) }}" method="POST" class="flex items-center gap-1 justify-center">
                        @csrf
                        <select name="type" class="px-1.5 py-1 border border-gray-200 rounded text-xs focus:ring-1 focus:ring-teal-400 outline-none">
                            <option value="in">+ เข้า</option>
                            <option value="out">- ออก</option>
                            <option value="adjust">= ตั้ง</option>
                        </select>
                        <input type="number" name="quantity" min="0" placeholder="จำนวน"
                            class="w-16 px-2 py-1 border border-gray-200 rounded text-xs text-center focus:ring-1 focus:ring-teal-400 outline-none">
                        <input type="text" name="reason" placeholder="เหตุผล" required
                            class="w-24 px-2 py-1 border border-gray-200 rounded text-xs focus:ring-1 focus:ring-teal-400 outline-none">
                        <button type="submit" class="px-2 py-1 bg-teal-600 text-white rounded text-xs hover:bg-teal-700 whitespace-nowrap">บันทึก</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">ไม่พบสินค้า</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

<div class="mt-4">{{ $products->withQueryString()->links() }}</div>
@endsection
