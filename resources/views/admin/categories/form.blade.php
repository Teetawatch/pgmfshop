@extends('admin.layout')
@section('title', $category ? 'แก้ไขหมวดหมู่' : 'เพิ่มหมวดหมู่')

@section('content')
<div class="max-w-2xl">
    <div class="mb-4">
        <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← กลับไปหน้าหมวดหมู่</a>
    </div>

    <div class="bg-white rounded-xl border p-6">
        <form method="POST" action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
            @csrf
            @if($category) @method('PUT') @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อหมวดหมู่ *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}"
                        placeholder="ปล่อยว่างเพื่อสร้างอัตโนมัติจากชื่อ"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                    <p class="text-xs text-gray-400 mt-1">ใช้สำหรับ URL เช่น /products?category=slug-name</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">คำอธิบาย</label>
                    <textarea name="description" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">{{ old('description', $category->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รูปภาพ (URL)</label>
                    <input type="text" name="image" value="{{ old('image', $category->image ?? '') }}"
                        placeholder="https://example.com/image.jpg"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                    @if($category && $category->image)
                        <div class="mt-2">
                            <img src="{{ $category->image }}" alt="" class="w-20 h-20 rounded-lg object-cover bg-gray-100">
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ลำดับการแสดงผล</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                        <p class="text-xs text-gray-400 mt-1">ตัวเลขน้อย = แสดงก่อน</p>
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="text-sm text-gray-700">เปิดใช้งาน</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-4 border-t">
                <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg text-sm hover:bg-teal-700 font-medium">
                    {{ $category ? 'บันทึกการแก้ไข' : 'เพิ่มหมวดหมู่' }}
                </button>
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection
