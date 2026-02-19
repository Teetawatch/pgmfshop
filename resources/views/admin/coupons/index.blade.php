@extends('admin.layout')
@section('title', 'จัดการคูปอง')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h2 class="text-sm text-gray-500">คูปองทั้งหมด {{ $coupons->total() }} รายการ</h2>
    <a href="{{ route('admin.coupons.create') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg text-sm hover:bg-teal-700">+ สร้างคูปอง</a>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500">
            <tr>
                <th class="px-5 py-3 text-left">โค้ด</th>
                <th class="px-5 py-3 text-left">รายละเอียด</th>
                <th class="px-5 py-3 text-center">ส่วนลด</th>
                <th class="px-5 py-3 text-center">ใช้แล้ว</th>
                <th class="px-5 py-3 text-center">วันหมดอายุ</th>
                <th class="px-5 py-3 text-center">สถานะ</th>
                <th class="px-5 py-3 text-center">จัดการ</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($coupons as $coupon)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <span class="font-mono font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded">{{ $coupon->code }}</span>
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $coupon->description ?? '-' }}</td>
                <td class="px-5 py-3 text-center font-medium">
                    @if($coupon->discount_type === 'percentage')
                        {{ $coupon->discount_value }}%
                        @if($coupon->max_discount) <span class="text-xs text-gray-400">(สูงสุด ฿{{ number_format($coupon->max_discount, 0) }})</span> @endif
                    @else
                        ฿{{ number_format($coupon->discount_value, 0) }}
                    @endif
                </td>
                <td class="px-5 py-3 text-center">
                    {{ $coupon->used_count }}{{ $coupon->usage_limit > 0 ? '/' . $coupon->usage_limit : '' }}
                </td>
                <td class="px-5 py-3 text-center text-xs text-gray-500">
                    {{ $coupon->end_date->format('d/m/Y') }}
                </td>
                <td class="px-5 py-3 text-center">
                    @php $valid = $coupon->isValid(); @endphp
                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-medium {{ $valid ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $valid ? 'ใช้งานได้' : 'หมดอายุ/ปิด' }}
                    </span>
                </td>
                <td class="px-5 py-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded text-xs hover:bg-blue-100">แก้ไข</a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('ยืนยันลบคูปองนี้?')">
                            @csrf @method('DELETE')
                            <button class="px-2.5 py-1 bg-red-50 text-red-600 rounded text-xs hover:bg-red-100">ลบ</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400">ยังไม่มีคูปอง</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $coupons->links() }}</div>
@endsection
