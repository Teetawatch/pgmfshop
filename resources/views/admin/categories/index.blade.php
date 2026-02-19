@extends('admin.layout')
@section('title', 'จัดการหมวดหมู่')

@section('content')
<div class="flex items-center justify-between mb-4">
    <form method="GET" class="flex items-center gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาหมวดหมู่..."
            class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-64 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-700">ค้นหา</button>
        @if(request('search'))
            <a href="{{ route('admin.categories.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700">ล้าง</a>
        @endif
    </form>
    <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg text-sm hover:bg-teal-700">+ เพิ่มหมวดหมู่</a>
</div>

@if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500">
            <tr>
                <th class="px-5 py-3 text-left">ลำดับ</th>
                <th class="px-5 py-3 text-left">หมวดหมู่</th>
                <th class="px-5 py-3 text-left">Slug</th>
                <th class="px-5 py-3 text-center">สินค้า</th>
                <th class="px-5 py-3 text-center">สถานะ</th>
                <th class="px-5 py-3 text-center">จัดการ</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($categories as $category)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-gray-500">{{ $category->sort_order }}</td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        @if($category->image)
                            <img src="{{ $category->image }}" alt="" class="w-10 h-10 rounded-lg object-cover bg-gray-100">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600 font-bold text-sm">
                                {{ mb_substr($category->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-800">{{ $category->name }}</p>
                            @if($category->description)
                                <p class="text-xs text-gray-400 truncate max-w-xs">{{ $category->description }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3 text-gray-500 font-mono text-xs">{{ $category->slug }}</td>
                <td class="px-5 py-3 text-center">
                    <span class="font-medium {{ $category->products_count > 0 ? 'text-gray-700' : 'text-gray-400' }}">
                        {{ $category->products_count }}
                    </span>
                </td>
                <td class="px-5 py-3 text-center">
                    @if($category->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-100 text-green-700">เปิดใช้งาน</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-500">ปิดใช้งาน</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="p-1.5 text-gray-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg" title="แก้ไข">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                              onsubmit="return confirm('ต้องการลบหมวดหมู่ &quot;{{ $category->name }}&quot; ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg" title="ลบ">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-12 text-center text-gray-400">ไม่พบหมวดหมู่</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($categories->hasPages())
    <div class="mt-4">
        {{ $categories->withQueryString()->links() }}
    </div>
@endif
@endsection
