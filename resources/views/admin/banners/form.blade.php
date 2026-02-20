@extends('admin.layout')
@section('title', $banner ? 'แก้ไขแบนเนอร์' : 'เพิ่มแบนเนอร์')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border p-6">
        <form method="POST" action="{{ $banner ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" enctype="multipart/form-data">
            @csrf
            @if($banner) @method('PUT') @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-600 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รูปภาพแบนเนอร์ {{ $banner ? '' : '*' }}</label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-teal-400 transition-colors cursor-pointer">
                        <input type="file" name="image" id="banner_image" accept="image/*"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" {{ $banner ? '' : 'required' }}>
                        <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.338-2.32 3.75 3.75 0 013.57 5.495A3.001 3.001 0 0118 19.5H6.75z"/>
                        </svg>
                        <p class="text-sm text-gray-500">คลิกหรือลากไฟล์มาวาง</p>
                        <p class="text-xs text-gray-400 mt-1">แนะนำขนาด 1200x400 px (สูงสุด 4MB)</p>
                    </div>
                    <div id="preview-new" class="mt-3 hidden">
                        <img id="preview-img" src="" alt="preview" class="w-full h-40 object-cover rounded-lg border">
                    </div>
                </div>

                @if($banner && $banner->image)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">รูปภาพปัจจุบัน</label>
                        <img src="{{ $banner->image }}" alt="{{ $banner->title }}" class="w-full h-40 object-cover rounded-lg border">
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">หัวข้อ (Title)</label>
                        <input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" placeholder="เช่น โปรโมชั่นพิเศษ"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">คำอธิบาย (Subtitle)</label>
                        <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle ?? '') }}" placeholder="เช่น ลดสูงสุด 50%"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ข้อความปุ่ม</label>
                        <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text ?? '') }}" placeholder="เช่น ช้อปเลย"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ลิงก์ปุ่ม</label>
                        <input type="text" name="button_link" value="{{ old('button_link', $banner->button_link ?? '') }}" placeholder="เช่น /products"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ลำดับการแสดง</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            เปิดใช้งาน
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-4 border-t">
                <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg text-sm font-medium hover:bg-teal-700">
                    {{ $banner ? 'บันทึกการแก้ไข' : 'เพิ่มแบนเนอร์' }}
                </button>
                <a href="{{ route('admin.banners.index') }}" class="px-6 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('banner_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('preview-img').src = ev.target.result;
                document.getElementById('preview-new').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
