@extends('admin.layout')
@section('title', 'จัดการแบนเนอร์')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-gray-500">ทั้งหมด {{ $banners->count() }} รายการ</p>
    <a href="{{ route('admin.banners.create') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg text-sm font-medium hover:bg-teal-700">+ เพิ่มแบนเนอร์</a>
</div>

@if($banners->count() > 0)
<div class="grid gap-4">
    @foreach($banners as $banner)
    <div class="bg-white rounded-xl border overflow-hidden flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 p-4">
        <img src="{{ $banner->image }}" alt="{{ $banner->title }}" class="w-full sm:w-48 h-32 sm:h-24 object-cover rounded-lg bg-gray-100 shrink-0">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
                <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $banner->title ?: '(ไม่มีหัวข้อ)' }}</h3>
                @if($banner->is_active)
                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-medium rounded-full shrink-0">เปิดใช้งาน</span>
                @else
                    <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-medium rounded-full shrink-0">ปิดใช้งาน</span>
                @endif
            </div>
            <p class="text-xs text-gray-500 truncate">{{ $banner->subtitle ?: '-' }}</p>
            <p class="text-xs text-gray-400 mt-1">ลำดับ: {{ $banner->sort_order }}</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('admin.banners.edit', $banner) }}" class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs hover:bg-blue-100">แก้ไข</a>
            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('ยืนยันลบแบนเนอร์นี้?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs hover:bg-red-100">ลบ</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="bg-white rounded-xl border p-12 text-center">
    <x-heroicon-o-photo class="mx-auto h-12 w-12 text-gray-300 mb-3" />
    <p class="text-sm text-gray-400">ยังไม่มีแบนเนอร์</p>
    <a href="{{ route('admin.banners.create') }}" class="inline-block mt-3 px-4 py-2 bg-teal-600 text-white rounded-lg text-sm hover:bg-teal-700">เพิ่มแบนเนอร์แรก</a>
</div>
@endif
@endsection
