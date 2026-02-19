@extends('admin.layout')
@section('title', 'จัดการคำสั่งซื้อ')

@php
    $colors = [
        'pending' => 'bg-gray-100 text-gray-600',
        'awaiting_payment' => 'bg-yellow-100 text-yellow-700',
        'paid' => 'bg-blue-100 text-blue-700',
        'processing' => 'bg-indigo-100 text-indigo-700',
        'shipped' => 'bg-purple-100 text-purple-700',
        'delivered' => 'bg-green-100 text-green-700',
        'cancelled' => 'bg-red-100 text-red-700',
        'expired' => 'bg-gray-200 text-gray-600',
    ];
    $labels = [
        'pending' => 'รอดำเนินการ',
        'awaiting_payment' => 'รอชำระ',
        'paid' => 'ชำระแล้ว',
        'processing' => 'กำลังจัดเตรียม',
        'shipped' => 'จัดส่งแล้ว',
        'delivered' => 'ส่งสำเร็จ',
        'cancelled' => 'ยกเลิก',
        'expired' => 'ไม่ชำระตามกำหนด',
    ];
@endphp

@section('content')
<!-- Status Tabs -->
<div class="flex items-center gap-2 mb-4 flex-wrap">
    <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ !request('status') ? 'bg-teal-600 text-white' : 'bg-white text-gray-600 border hover:bg-gray-50' }}">
        ทั้งหมด ({{ $statusCounts['all'] }})
    </a>
    @foreach(['awaiting_payment','paid','processing','shipped','delivered','cancelled','expired'] as $s)
        <a href="{{ route('admin.orders.index', ['status' => $s]) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ request('status') === $s ? 'bg-teal-600 text-white' : 'bg-white text-gray-600 border hover:bg-gray-50' }}">
            {{ $labels[$s] }} ({{ $statusCounts[$s] ?? 0 }})
        </a>
    @endforeach
</div>

<!-- Search -->
<form method="GET" class="mb-4">
    @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
    <div class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาหมายเลขคำสั่งซื้อ..."
            class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-72 focus:ring-2 focus:ring-teal-500 outline-none">
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-700">ค้นหา</button>
    </div>
</form>

<div class="bg-white rounded-xl border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500">
            <tr>
                <th class="px-5 py-3 text-left">หมายเลข</th>
                <th class="px-5 py-3 text-left">ลูกค้า</th>
                <th class="px-5 py-3 text-center">สถานะ</th>
                <th class="px-5 py-3 text-right">ยอดรวม</th>
                <th class="px-5 py-3 text-left">วันที่</th>
                <th class="px-5 py-3 text-center">จัดการ</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($orders as $order)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-medium text-gray-800">{{ $order->order_number }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $order->user->name ?? '-' }}</td>
                <td class="px-5 py-3 text-center">
                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-medium {{ $colors[$order->status] ?? '' }}">
                        {{ $labels[$order->status] ?? $order->status }}
                    </span>
                </td>
                <td class="px-5 py-3 text-right font-medium">฿{{ number_format($order->total, 0) }}</td>
                <td class="px-5 py-3 text-gray-500 text-xs">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-5 py-3 text-center">
                    <div class="flex items-center justify-center gap-1.5">
                        <a href="{{ route('admin.orders.show', $order) }}" class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded text-xs hover:bg-blue-100">ดูรายละเอียด</a>
                        @if(!in_array($order->status, ['cancelled', 'delivered', 'expired']))
                            <button type="button" onclick="openCancelModal('{{ $order->id }}', '{{ $order->order_number }}')"
                                class="px-2.5 py-1 bg-red-50 text-red-600 rounded text-xs hover:bg-red-100">ยกเลิก</button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">ไม่พบคำสั่งซื้อ</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $orders->withQueryString()->links() }}</div>

<!-- Cancel Confirmation Modal -->
<div id="cancelModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/50" onclick="closeCancelModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 relative">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">ยืนยันยกเลิกคำสั่งซื้อ</h3>
            <p class="text-sm text-gray-500 mb-1">คุณต้องการยกเลิกคำสั่งซื้อ <span id="cancelOrderNumber" class="font-semibold text-gray-800"></span> ใช่หรือไม่?</p>
            <p class="text-xs text-red-500 mb-4">สต็อกสินค้าจะถูกคืนกลับอัตโนมัติ</p>
            <form id="cancelForm" method="POST">
                @csrf @method('PATCH')
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-600 mb-1">เหตุผลในการยกเลิก</label>
                    <input type="text" name="cancel_reason" placeholder="เช่น ลูกค้าขอยกเลิก, สินค้าหมด"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-red-300">
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="closeCancelModal()" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200">ยกเลิก</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">ยืนยันยกเลิกคำสั่งซื้อ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openCancelModal(orderId, orderNumber) {
        document.getElementById('cancelOrderNumber').textContent = orderNumber;
        document.getElementById('cancelForm').action = '/admin/orders/' + orderId + '/cancel';
        document.getElementById('cancelModal').classList.remove('hidden');
    }
    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }
</script>
@endsection
