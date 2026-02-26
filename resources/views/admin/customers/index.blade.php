@extends('admin.layout')
@section('title', 'จัดการลูกค้า')

@section('content')
<form method="GET" class="mb-4">
    <div class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อ, อีเมล..."
            class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-full sm:w-72 focus:ring-2 focus:ring-teal-500 outline-none">
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-700">ค้นหา</button>
    </div>
</form>

<div class="bg-white rounded-xl border overflow-hidden">
<div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[700px]">
        <thead class="bg-gray-50 text-xs text-gray-500">
            <tr>
                <th class="px-5 py-3 text-left">ลูกค้า</th>
                <th class="px-5 py-3 text-left">อีเมล</th>
                <th class="px-5 py-3 text-left">โทรศัพท์</th>
                <th class="px-5 py-3 text-center">คำสั่งซื้อ</th>
                <th class="px-5 py-3 text-right">ยอดซื้อรวม</th>
                <th class="px-5 py-3 text-left">สมัครเมื่อ</th>
                <th class="px-5 py-3 text-center">จัดการ</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($customers as $customer)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        @php $customerAvatar = $customer->avatar ?? $customer->social_avatar ?? null; @endphp
                        @if($customerAvatar)
                            <img src="{{ $customerAvatar }}" alt="{{ $customer->name }}"
                                class="w-9 h-9 rounded-full object-cover border border-gray-200 shadow-sm shrink-0">
                        @else
                            <div class="w-9 h-9 rounded-full bg-linear-to-br from-teal-400 to-teal-600 flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-sm">
                                {{ mb_substr($customer->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-800 leading-tight">{{ $customer->name }}</p>
                            @if($customer->email_verified_at)
                                <span class="text-[10px] text-emerald-600 flex items-center gap-0.5">
                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    ยืนยันอีเมลแล้ว
                                </span>
                            @else
                                <span class="text-[10px] text-amber-500">ยังไม่ยืนยัน</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $customer->email }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $customer->phone ?? '-' }}</td>
                <td class="px-5 py-3 text-center">{{ $customer->orders_count }}</td>
                <td class="px-5 py-3 text-right font-medium">฿{{ number_format($customer->orders_sum_total ?? 0, 0) }}</td>
                <td class="px-5 py-3 text-gray-500 text-xs">{{ $customer->created_at->format('d/m/Y') }}</td>
                <td class="px-5 py-3 text-center">
                    <a href="{{ route('admin.customers.show', $customer) }}" class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded text-xs hover:bg-blue-100">ดูข้อมูล</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400">ไม่พบลูกค้า</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

<div class="mt-4">{{ $customers->withQueryString()->links() }}</div>
@endsection
