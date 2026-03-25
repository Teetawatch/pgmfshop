@extends('admin.layout')
@section('title', 'รายงานยอดขาย')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-800">รายงานยอดขาย</h2>
            <p class="text-sm text-gray-500">ข้อมูล {{ $startDate->format('d/m/Y') }} — {{ $endDate->format('d/m/Y') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.sales.pdf', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                PDF
            </a>
            <a href="{{ route('admin.reports.sales.excel', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                <x-heroicon-o-table-cells class="w-4 h-4" />
                Excel
            </a>
        </div>
    </div>

    {{-- Period Filter --}}
    <form method="GET" class="bg-white rounded-lg border p-4 mb-6">
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1 block">ช่วงเวลา</label>
                <select name="period" onchange="toggleCustomDate(this.value)" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary">
                    @foreach(['7days' => '7 วัน', '30days' => '30 วัน', '90days' => '90 วัน', 'this_month' => 'เดือนนี้', 'last_month' => 'เดือนที่แล้ว', 'this_year' => 'ปีนี้', 'custom' => 'กำหนดเอง'] as $key => $label)
                        <option value="{{ $key }}" {{ $period === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div id="customDates" class="flex gap-2 {{ $period === 'custom' ? '' : 'hidden' }}">
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1 block">จาก</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1 block">ถึง</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary text-white text-sm rounded-lg hover:opacity-90 transition-colors">กรอง</button>
        </div>
    </form>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border p-4">
            <p class="text-xs text-gray-500 mb-1">ยอดขายรวม</p>
            <p class="text-xl font-bold text-primary">฿{{ number_format($summary['total_revenue'], 0) }}</p>
        </div>
        <div class="bg-white rounded-lg border p-4">
            <p class="text-xs text-gray-500 mb-1">จำนวนคำสั่งซื้อ</p>
            <p class="text-xl font-bold text-gray-800">{{ number_format($summary['total_orders']) }}</p>
        </div>
        <div class="bg-white rounded-lg border p-4">
            <p class="text-xs text-gray-500 mb-1">สินค้าที่ขาย</p>
            <p class="text-xl font-bold text-gray-800">{{ number_format($summary['total_items']) }} ชิ้น</p>
        </div>
        <div class="bg-white rounded-lg border p-4">
            <p class="text-xs text-gray-500 mb-1">ค่าเฉลี่ยต่อออเดอร์</p>
            <p class="text-xl font-bold text-gray-800">฿{{ number_format($summary['avg_order_value'], 0) }}</p>
        </div>
    </div>

    {{-- Revenue Chart --}}
    <div class="bg-white rounded-lg border p-4 mb-6">
        <h3 class="text-sm font-bold text-gray-700 mb-4">กราฟยอดขายรายวัน</h3>
        <div style="height: 300px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mb-6">
        {{-- Payment Method --}}
        <div class="bg-white rounded-lg border p-4">
            <h3 class="text-sm font-bold text-gray-700 mb-4">แยกตามวิธีชำระเงิน</h3>
            @php
                $paymentLabels = ['promptpay' => 'PromptPay', 'bank_transfer' => 'โอนเงิน'];
            @endphp
            <div class="space-y-3">
                @foreach($paymentBreakdown as $pm)
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full {{ $pm->payment_method === 'promptpay' ? 'bg-blue-500' : 'bg-orange-500' }}"></div>
                            <span>{{ $paymentLabels[$pm->payment_method] ?? $pm->payment_method }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-medium">{{ $pm->count }} ออเดอร์</span>
                            <span class="text-gray-400 ml-2">฿{{ number_format($pm->total, 0) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Status Breakdown --}}
        <div class="bg-white rounded-lg border p-4">
            <h3 class="text-sm font-bold text-gray-700 mb-4">แยกตามสถานะ</h3>
            @php
                $statusLabels = [
                    'pending' => 'รอดำเนินการ', 'awaiting_payment' => 'รอชำระเงิน',
                    'paid' => 'ชำระแล้ว', 'processing' => 'กำลังจัดเตรียม',
                    'shipped' => 'จัดส่งแล้ว', 'delivered' => 'ส่งสำเร็จ',
                    'cancelled' => 'ยกเลิก', 'expired' => 'หมดอายุ',
                ];
                $statusColors = [
                    'pending' => 'bg-yellow-400', 'awaiting_payment' => 'bg-orange-400',
                    'paid' => 'bg-blue-400', 'processing' => 'bg-indigo-400',
                    'shipped' => 'bg-purple-400', 'delivered' => 'bg-green-400',
                    'cancelled' => 'bg-red-400', 'expired' => 'bg-gray-400',
                ];
            @endphp
            <div class="space-y-3">
                @foreach($statusBreakdown as $st)
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full {{ $statusColors[$st->status] ?? 'bg-gray-400' }}"></div>
                            <span>{{ $statusLabels[$st->status] ?? $st->status }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-medium">{{ $st->count }} ออเดอร์</span>
                            <span class="text-gray-400 ml-2">฿{{ number_format($st->total, 0) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Daily Breakdown Table --}}
    <div class="bg-white rounded-lg border overflow-hidden">
        <div class="px-4 py-3 border-b">
            <h3 class="text-sm font-bold text-gray-700">รายละเอียดรายวัน</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500">
                        <th class="px-4 py-3 font-medium">วันที่</th>
                        <th class="px-4 py-3 font-medium text-right">ยอดขาย</th>
                        <th class="px-4 py-3 font-medium text-center">คำสั่งซื้อ</th>
                        <th class="px-4 py-3 font-medium text-right">ส่วนลด</th>
                        <th class="px-4 py-3 font-medium text-right">ค่าจัดส่ง</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($dailyData->reverse() as $day)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-right font-medium">฿{{ number_format($day->revenue, 0) }}</td>
                            <td class="px-4 py-3 text-center">{{ $day->orders }}</td>
                            <td class="px-4 py-3 text-right text-red-500">{{ $day->discount > 0 ? '-฿'.number_format($day->discount, 0) : '-' }}</td>
                            <td class="px-4 py-3 text-right">฿{{ number_format($day->shipping, 0) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">ไม่มีข้อมูลในช่วงที่เลือก</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    function toggleCustomDate(val) {
        document.getElementById('customDates').classList.toggle('hidden', val !== 'custom');
    }

    new Chart(document.getElementById('salesChart'), {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    label: 'ยอดขาย (บาท)',
                    data: @json($chartRevenue),
                    backgroundColor: 'rgba(13, 148, 136, 0.15)',
                    borderColor: '#0d9488',
                    borderWidth: 2,
                    borderRadius: 4,
                    yAxisID: 'y',
                },
                {
                    label: 'คำสั่งซื้อ',
                    data: @json($chartOrders),
                    type: 'line',
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 2,
                    pointRadius: 3,
                    tension: 0.3,
                    fill: true,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'top', labels: { font: { size: 11 } } } },
            scales: {
                y: { position: 'left', beginAtZero: true, ticks: { callback: v => '฿' + v.toLocaleString() } },
                y1: { position: 'right', beginAtZero: true, grid: { drawOnChartArea: false } },
            }
        }
    });
</script>
@endsection
