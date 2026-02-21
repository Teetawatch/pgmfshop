@extends('admin.layout')
@section('title', 'สินค้าขายดี')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-800">รายงานสินค้าขายดี</h2>
            <p class="text-sm text-gray-500">ข้อมูล {{ $startDate->format('d/m/Y') }} — {{ $endDate->format('d/m/Y') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.best-selling.pdf', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                PDF
            </a>
            <a href="{{ route('admin.reports.best-selling.excel', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                <x-heroicon-o-table-cells class="w-4 h-4" />
                Excel
            </a>
        </div>
    </div>

    {{-- Filters --}}
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
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1 block">หมวดหมู่</label>
                <select name="category" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary">
                    <option value="">ทั้งหมด</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1 block">จำนวน</label>
                <select name="limit" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary">
                    @foreach([10, 20, 50, 100] as $l)
                        <option value="{{ $l }}" {{ $limit == $l ? 'selected' : '' }}>{{ $l }} รายการ</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary text-white text-sm rounded-lg hover:opacity-90 transition-colors">กรอง</button>
        </div>
    </form>

    {{-- Summary --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg border p-4">
            <p class="text-xs text-gray-500 mb-1">สินค้าที่ขายได้</p>
            <p class="text-xl font-bold text-gray-800">{{ number_format($summary['unique_products']) }} รายการ</p>
        </div>
        <div class="bg-white rounded-lg border p-4">
            <p class="text-xs text-gray-500 mb-1">จำนวนขายรวม</p>
            <p class="text-xl font-bold text-primary">{{ number_format($summary['total_products_sold']) }} ชิ้น</p>
        </div>
        <div class="bg-white rounded-lg border p-4">
            <p class="text-xs text-gray-500 mb-1">ยอดขายรวม</p>
            <p class="text-xl font-bold text-primary">฿{{ number_format($summary['total_revenue'], 0) }}</p>
        </div>
    </div>

    {{-- Chart: Top 10 --}}
    @if($products->count() > 0)
    <div class="bg-white rounded-lg border p-4 mb-6">
        <h3 class="text-sm font-bold text-gray-700 mb-4">สินค้าขายดี Top 10</h3>
        <div style="height: 300px;">
            <canvas id="bestSellingChart"></canvas>
        </div>
    </div>
    @endif

    {{-- Products Table --}}
    <div class="bg-white rounded-lg border overflow-hidden">
        <div class="px-4 py-3 border-b">
            <h3 class="text-sm font-bold text-gray-700">รายละเอียดสินค้าขายดี</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500">
                        <th class="px-4 py-3 font-medium text-center w-10">#</th>
                        <th class="px-4 py-3 font-medium">สินค้า</th>
                        <th class="px-4 py-3 font-medium">หมวดหมู่</th>
                        <th class="px-4 py-3 font-medium text-center">จำนวนขาย</th>
                        <th class="px-4 py-3 font-medium text-right">ยอดขาย</th>
                        <th class="px-4 py-3 font-medium text-center">ออเดอร์</th>
                        <th class="px-4 py-3 font-medium text-right">ราคาเฉลี่ย</th>
                        <th class="px-4 py-3 font-medium text-center">สต็อก</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $idx => $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-center font-bold text-gray-400">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($p->image)
                                        <img src="{{ $p->image }}" class="w-10 h-10 rounded-lg object-cover border" alt="">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <x-heroicon-o-cube class="w-4 h-4 text-gray-400" />
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-800 line-clamp-1">{{ $p->product_name }}</p>
                                        <div class="flex items-center gap-0.5 mt-0.5">
                                            @for($s = 1; $s <= 5; $s++)
                                                <svg class="w-3 h-3 {{ $s <= round($p->rating) ? 'text-amber-400 fill-amber-400' : 'text-gray-200' }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $p->category_name }}</td>
                            <td class="px-4 py-3 text-center font-bold text-primary">{{ number_format($p->total_sold) }}</td>
                            <td class="px-4 py-3 text-right font-medium">฿{{ number_format($p->total_revenue, 0) }}</td>
                            <td class="px-4 py-3 text-center">{{ number_format($p->order_count) }}</td>
                            <td class="px-4 py-3 text-right">฿{{ number_format($p->avg_price, 0) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium {{ $p->current_stock === 0 ? 'bg-red-50 text-red-700' : ($p->current_stock <= 10 ? 'bg-amber-50 text-amber-700' : 'bg-green-50 text-green-700') }}">
                                    {{ $p->current_stock }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">ไม่มีข้อมูลในช่วงที่เลือก</td></tr>
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

    @if($products->count() > 0)
    new Chart(document.getElementById('bestSellingChart'), {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    label: 'จำนวนขาย (ชิ้น)',
                    data: @json($chartSold),
                    backgroundColor: 'rgba(13, 148, 136, 0.7)',
                    borderColor: '#0d9488',
                    borderWidth: 1,
                    borderRadius: 6,
                    yAxisID: 'y',
                },
                {
                    label: 'ยอดขาย (บาท)',
                    data: @json($chartRevenue),
                    type: 'line',
                    borderColor: '#6366f1',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#6366f1',
                    tension: 0.3,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'top', labels: { font: { size: 11 } } } },
            scales: {
                y: { ticks: { font: { size: 11 } } },
                x: { position: 'bottom', beginAtZero: true },
                y1: { position: 'right', beginAtZero: true, grid: { drawOnChartArea: false }, ticks: { callback: v => '฿' + v.toLocaleString() } },
            }
        }
    });
    @endif
</script>
@endsection
