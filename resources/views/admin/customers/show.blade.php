@extends('admin.layout')
@section('title', 'ข้อมูลลูกค้า: ' . $user->name)

@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    <!-- Profile -->
    <div class="bg-white rounded-xl border p-5">
        <div class="text-center mb-4">
            <div class="w-16 h-16 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-2xl font-bold mx-auto mb-3">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
            <h2 class="text-lg font-bold text-gray-800">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
        </div>
        <div class="border-t pt-4 space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">โทรศัพท์</span><span>{{ $user->phone ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">สมัครเมื่อ</span><span>{{ $user->created_at->format('d/m/Y') }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">คำสั่งซื้อ</span><span class="font-medium">{{ $user->orders_count }} รายการ</span></div>
            <div class="flex justify-between"><span class="text-gray-500">ยอดซื้อรวม</span><span class="font-bold text-teal-600">฿{{ number_format($user->orders_sum_total ?? 0, 0) }}</span></div>
        </div>

        @if($user->addresses)
        <div class="border-t pt-4 mt-4">
            <h3 class="text-xs font-semibold text-gray-600 mb-2">ที่อยู่</h3>
            @foreach($user->addresses as $addr)
            <div class="text-xs text-gray-500 mb-2 p-2 bg-gray-50 rounded">
                <p class="font-medium text-gray-700">{{ $addr['name'] ?? '' }}</p>
                <p>{{ $addr['address'] ?? '' }}</p>
                <p>{{ $addr['district'] ?? '' }} {{ $addr['province'] ?? '' }} {{ $addr['postalCode'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Orders -->
    <div class="lg:col-span-2 bg-white rounded-xl border">
        <div class="px-5 py-4 border-b">
            <h2 class="text-sm font-semibold text-gray-800">คำสั่งซื้อล่าสุด</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500">
                    <tr>
                        <th class="px-5 py-2.5 text-left">หมายเลข</th>
                        <th class="px-5 py-2.5 text-center">สถานะ</th>
                        <th class="px-5 py-2.5 text-right">ยอด</th>
                        <th class="px-5 py-2.5 text-left">วันที่</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($user->orders as $order)
                    @php
                        $colors = [
                            'pending' => 'bg-gray-100 text-gray-600', 'awaiting_payment' => 'bg-yellow-100 text-yellow-700',
                            'paid' => 'bg-blue-100 text-blue-700', 'processing' => 'bg-indigo-100 text-indigo-700',
                            'shipped' => 'bg-purple-100 text-purple-700', 'delivered' => 'bg-green-100 text-green-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                        ];
                        $labels = [
                            'pending' => 'รอดำเนินการ', 'awaiting_payment' => 'รอชำระ', 'paid' => 'ชำระแล้ว',
                            'processing' => 'จัดเตรียม', 'shipped' => 'จัดส่งแล้ว', 'delivered' => 'สำเร็จ', 'cancelled' => 'ยกเลิก',
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-teal-600 hover:underline font-medium">{{ $order->order_number }}</a>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-medium {{ $colors[$order->status] ?? '' }}">{{ $labels[$order->status] ?? $order->status }}</span>
                        </td>
                        <td class="px-5 py-3 text-right font-medium">฿{{ number_format($order->total, 0) }}</td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">ยังไม่มีคำสั่งซื้อ</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
