@extends('admin.layout')
@section('title', $coupon ? 'แก้ไขคูปอง' : 'สร้างคูปอง')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border p-6">
        <form method="POST" action="{{ $coupon ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}">
            @csrf
            @if($coupon) @method('PUT') @endif

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">โค้ดคูปอง *</label>
                        <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono uppercase focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทส่วนลด *</label>
                        <select name="discount_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                            <option value="percentage" {{ old('discount_type', $coupon->discount_type ?? '') === 'percentage' ? 'selected' : '' }}>เปอร์เซ็นต์ (%)</option>
                            <option value="fixed" {{ old('discount_type', $coupon->discount_type ?? '') === 'fixed' ? 'selected' : '' }}>จำนวนเงิน (฿)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด</label>
                    <input type="text" name="description" value="{{ old('description', $coupon->description ?? '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">มูลค่าส่วนลด *</label>
                        <input type="number" name="discount_value" value="{{ old('discount_value', $coupon->discount_value ?? '') }}" min="0" step="0.01" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ส่วนลดสูงสุด (฿)</label>
                        <input type="number" name="max_discount" value="{{ old('max_discount', $coupon->max_discount ?? '') }}" min="0" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ยอดขั้นต่ำ (฿)</label>
                        <input type="number" name="min_purchase" value="{{ old('min_purchase', $coupon->min_purchase ?? 0) }}" min="0" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">จำกัดการใช้ (0=ไม่จำกัด)</label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit ?? 0) }}" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">วันเริ่มต้น *</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $coupon ? $coupon->start_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">วันหมดอายุ *</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $coupon ? $coupon->end_date->format('Y-m-d') : now()->addMonth()->format('Y-m-d')) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    เปิดใช้งาน
                </label>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-4 border-t">
                <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg text-sm font-medium hover:bg-teal-700">
                    {{ $coupon ? 'บันทึกการแก้ไข' : 'สร้างคูปอง' }}
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="px-6 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection
