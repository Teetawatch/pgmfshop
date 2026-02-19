@extends('admin.layout')
@section('title', 'จัดการลูกค้า')

@section('content')
<form method="GET" class="mb-4">
    <div class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อ, อีเมล..."
            class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-72 focus:ring-2 focus:ring-teal-500 outline-none">
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-700">ค้นหา</button>
    </div>
</form>

<div class="bg-white rounded-xl border overflow-hidden">
    <table class="w-full text-sm">
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
                        <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-xs font-bold">
                            {{ mb_substr($customer->name, 0, 1) }}
                        </div>
                        <span class="font-medium text-gray-800">{{ $customer->name }}</span>
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

<div class="mt-4">{{ $customers->withQueryString()->links() }}</div>
@endsection
