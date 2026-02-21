@extends('admin.layout')
@section('title', 'รายงานสต็อกต่ำ')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-800">รายงานสต็อกต่ำ</h2>
            <p class="text-sm text-gray-500">สินค้าที่มีสต็อก ≤ {{ $threshold }} ชิ้น</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.low-stock.pdf', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                PDF
            </a>
            <a href="{{ route('admin.reports.low-stock.excel', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                <x-heroicon-o-table-cells class="w-4 h-4" />
                Excel
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-lg border p-4 mb-6">
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1 block">เกณฑ์สต็อกต่ำ</label>
                <select name="threshold" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary">
                    @foreach([5, 10, 20, 50, 100] as $t)
                        <option value="{{ $t }}" {{ $threshold == $t ? 'selected' : '' }}>≤ {{ $t }} ชิ้น</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1 block">หมวดหมู่</label>
                <select name="category" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary">
                    <option value="">ทั้งหมด</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1 block">เรียงตาม</label>
                <select name="sort" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary">
                    <option value="stock_asc" {{ $sortBy === 'stock_asc' ? 'selected' : '' }}>สต็อกน้อย → มาก</option>
                    <option value="stock_desc" {{ $sortBy === 'stock_desc' ? 'selected' : '' }}>สต็อกมาก → น้อย</option>
                    <option value="sold_desc" {{ $sortBy === 'sold_desc' ? 'selected' : '' }}>ขายดี → น้อย</option>
                    <option value="name_asc" {{ $sortBy === 'name_asc' ? 'selected' : '' }}>ชื่อ ก-ฮ</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary text-white text-sm rounded-lg hover:opacity-90 transition-colors">กรอง</button>
        </div>
    </form>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg border p-4 border-l-4 border-l-red-500">
            <p class="text-xs text-gray-500 mb-1">สินค้าหมด</p>
            <p class="text-2xl font-bold text-red-600">{{ $summary['out_of_stock'] }}</p>
        </div>
        <div class="bg-white rounded-lg border p-4 border-l-4 border-l-orange-500">
            <p class="text-xs text-gray-500 mb-1">วิกฤต (1-5)</p>
            <p class="text-2xl font-bold text-orange-600">{{ $summary['critical'] }}</p>
        </div>
        <div class="bg-white rounded-lg border p-4 border-l-4 border-l-amber-500">
            <p class="text-xs text-gray-500 mb-1">สต็อกต่ำ (6-{{ $threshold }})</p>
            <p class="text-2xl font-bold text-amber-600">{{ $summary['low'] }}</p>
        </div>
        <div class="bg-white rounded-lg border p-4 border-l-4 border-l-gray-400">
            <p class="text-xs text-gray-500 mb-1">รวมทั้งหมด</p>
            <p class="text-2xl font-bold text-gray-800">{{ $summary['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg border p-4 border-l-4 border-l-primary">
            <p class="text-xs text-gray-500 mb-1">มูลค่าคงเหลือ</p>
            <p class="text-xl font-bold text-primary">฿{{ number_format($summary['total_value'], 0) }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-4 gap-6 mb-6">
        {{-- Category Breakdown --}}
        <div class="bg-white rounded-lg border p-4">
            <h3 class="text-sm font-bold text-gray-700 mb-4">แยกตามหมวดหมู่</h3>
            <div class="space-y-2.5">
                @foreach($categoryBreakdown as $name => $data)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">{{ $name }}</span>
                        <div class="flex items-center gap-2">
                            <span class="font-medium">{{ $data['count'] }}</span>
                            @if($data['out_of_stock'] > 0)
                                <span class="text-xs text-red-500">({{ $data['out_of_stock'] }} หมด)</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Products Table --}}
        <div class="lg:col-span-3 bg-white rounded-lg border overflow-hidden">
            <div class="px-4 py-3 border-b">
                <h3 class="text-sm font-bold text-gray-700">รายการสินค้า ({{ $products->count() }} รายการ)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs text-gray-500">
                            <th class="px-4 py-3 font-medium">สินค้า</th>
                            <th class="px-4 py-3 font-medium">หมวดหมู่</th>
                            <th class="px-4 py-3 font-medium text-center">สต็อก</th>
                            <th class="px-4 py-3 font-medium text-center">ขายแล้ว</th>
                            <th class="px-4 py-3 font-medium text-right">ราคา</th>
                            <th class="px-4 py-3 font-medium text-right">มูลค่าคงเหลือ</th>
                            <th class="px-4 py-3 font-medium text-center">สถานะ</th>
                            <th class="px-4 py-3 font-medium text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($products as $p)
                            @php
                                $image = is_array($p->images) ? ($p->images[0] ?? '') : '';
                            @endphp
                            <tr class="hover:bg-gray-50 {{ $p->stock === 0 ? 'bg-red-50/50' : '' }}">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($image)
                                            <img src="{{ $image }}" class="w-10 h-10 rounded-lg object-cover border" alt="">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <x-heroicon-o-cube class="w-4 h-4 text-gray-400" />
                                            </div>
                                        @endif
                                        <p class="font-medium text-gray-800 line-clamp-1">{{ $p->name }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ $p->category->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-0.5 rounded text-xs font-bold {{ $p->stock === 0 ? 'bg-red-100 text-red-700' : ($p->stock <= 5 ? 'bg-orange-100 text-orange-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ $p->stock }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-500">{{ number_format($p->sold) }}</td>
                                <td class="px-4 py-3 text-right">฿{{ number_format($p->price, 0) }}</td>
                                <td class="px-4 py-3 text-right font-medium">฿{{ number_format($p->stock * $p->price, 0) }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($p->stock === 0)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">หมด</span>
                                    @elseif($p->stock <= 5)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">วิกฤต</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">ต่ำ</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.stock.show', $p->id) }}" class="inline-flex items-center gap-1 px-2 py-1 text-xs text-primary hover:bg-primary/10 rounded transition-colors">
                                        <x-heroicon-o-pencil-square class="w-3.5 h-3.5" />
                                        ปรับสต็อก
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">ไม่มีสินค้าที่ต่ำกว่าเกณฑ์</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
