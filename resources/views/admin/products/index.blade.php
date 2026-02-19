@extends('admin.layout')
@section('title', 'จัดการสินค้า')

@section('content')
<div class="flex items-center justify-between mb-4">
    <form method="GET" class="flex items-center gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาสินค้า..."
            class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-64 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
        <select name="category" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
            <option value="">ทุกหมวดหมู่</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="product_type" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
            <option value="">ทุกประเภท</option>
            @foreach(\App\Models\Product::PRODUCT_TYPES as $typeKey => $typeLabel)
                <option value="{{ $typeKey }}" {{ request('product_type') == $typeKey ? 'selected' : '' }}>{{ $typeLabel }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-700">ค้นหา</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg text-sm hover:bg-teal-700">+ เพิ่มสินค้า</a>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500">
            <tr>
                <th class="px-5 py-3 text-left">สินค้า</th>
                <th class="px-5 py-3 text-left">หมวดหมู่</th>
                <th class="px-5 py-3 text-right">ราคา</th>
                <th class="px-5 py-3 text-center">สต็อก</th>
                <th class="px-5 py-3 text-center">ขายแล้ว</th>
                <th class="px-5 py-3 text-center">สถานะ</th>
                <th class="px-5 py-3 text-center">จัดการ</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ $product->images[0] }}" alt="" class="w-10 h-10 rounded-lg object-cover bg-gray-100">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-xs">N/A</div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-800">{{ $product->name }}</p>
                            <div class="flex gap-1 mt-0.5">
                                @php
                                    $typeBadge = match($product->product_type) {
                                        'book' => 'bg-blue-100 text-blue-600',
                                        'clothing' => 'bg-purple-100 text-purple-600',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="text-[9px] {{ $typeBadge }} px-1.5 py-0.5 rounded font-medium">{{ $product->product_type_label }}</span>
                                @if($product->is_featured)<span class="text-[9px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded font-medium">HOT</span>@endif
                                @if($product->is_new)<span class="text-[9px] bg-green-100 text-green-600 px-1.5 py-0.5 rounded font-medium">NEW</span>@endif
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $product->category->name ?? '-' }}</td>
                <td class="px-5 py-3 text-right">
                    <p class="font-medium">฿{{ number_format($product->price, 0) }}</p>
                    @if($product->original_price)
                        <p class="text-xs text-gray-400 line-through">฿{{ number_format($product->original_price, 0) }}</p>
                    @endif
                </td>
                <td class="px-5 py-3 text-center">
                    <span class="{{ $product->stock < 20 ? 'text-red-600 font-bold' : 'text-gray-700' }}">{{ $product->stock }}</span>
                </td>
                <td class="px-5 py-3 text-center text-gray-600">{{ number_format($product->sold) }}</td>
                <td class="px-5 py-3 text-center">
                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-medium {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $product->is_active ? 'เปิดขาย' : 'ปิด' }}
                    </span>
                </td>
                <td class="px-5 py-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <a href="{{ route('admin.products.edit', $product) }}" class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded text-xs hover:bg-blue-100">แก้ไข</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('ยืนยันลบสินค้านี้?')">
                            @csrf @method('DELETE')
                            <button class="px-2.5 py-1 bg-red-50 text-red-600 rounded text-xs hover:bg-red-100">ลบ</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400">ไม่พบสินค้า</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $products->withQueryString()->links() }}</div>
@endsection
