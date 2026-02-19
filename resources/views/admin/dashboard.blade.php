@extends('admin.layout')
@section('title', 'แดชบอร์ด')

@section('content')
<!-- Stats Cards Row 1: Revenue -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="bg-white rounded-xl p-5 border">
        <div class="flex items-center justify-between mb-1">
            <p class="text-xs text-gray-500">รายได้ทั้งหมด</p>
            <span class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-800">฿{{ number_format($stats['total_revenue'], 0) }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 border">
        <div class="flex items-center justify-between mb-1">
            <p class="text-xs text-gray-500">รายได้วันนี้</p>
            <span class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-800">฿{{ number_format($stats['today_revenue'], 0) }}</p>
        <p class="text-[10px] text-gray-400 mt-0.5">{{ $stats['today_orders'] }} คำสั่งซื้อวันนี้</p>
    </div>
    <div class="bg-white rounded-xl p-5 border">
        <div class="flex items-center justify-between mb-1">
            <p class="text-xs text-gray-500">รายได้เดือนนี้</p>
            <span class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-800">฿{{ number_format($stats['month_revenue'], 0) }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 border">
        <div class="flex items-center justify-between mb-1">
            <p class="text-xs text-gray-500">คำสั่งซื้อทั้งหมด</p>
            <span class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_orders']) }}</p>
        @if($stats['pending_orders'] > 0)
            <p class="text-[10px] text-orange-500 mt-0.5">{{ $stats['pending_orders'] }} รอดำเนินการ</p>
        @endif
    </div>
</div>

<!-- Stats Cards Row 2 -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 border">
        <div class="flex items-center justify-between mb-1">
            <p class="text-xs text-gray-500">สินค้า</p>
            <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_products']) }}</p>
        @if($stats['low_stock'] > 0)
            <p class="text-[10px] text-red-500 mt-0.5">{{ $stats['low_stock'] }} สต็อกต่ำ</p>
        @endif
    </div>
    <div class="bg-white rounded-xl p-5 border">
        <div class="flex items-center justify-between mb-1">
            <p class="text-xs text-gray-500">ลูกค้า</p>
            <span class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_customers']) }}</p>
        <p class="text-[10px] text-teal-600 mt-0.5">+{{ $stats['new_customers_month'] }} เดือนนี้</p>
    </div>
    <div class="bg-white rounded-xl p-5 border">
        <div class="flex items-center justify-between mb-1">
            <p class="text-xs text-gray-500">รีวิว</p>
            <span class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_reviews']) }}</p>
        <p class="text-[10px] text-yellow-600 mt-0.5">เฉลี่ย {{ $stats['avg_rating'] }} ดาว</p>
    </div>
    <div class="bg-white rounded-xl p-5 border">
        <div class="flex items-center justify-between mb-1">
            <p class="text-xs text-gray-500">คูปอง</p>
            <span class="w-8 h-8 rounded-lg bg-pink-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['active_coupons'] }}</p>
        <p class="text-[10px] text-gray-400 mt-0.5">ใช้งานได้</p>
    </div>
</div>

<!-- Charts Row -->
<div class="grid lg:grid-cols-3 gap-6 mb-6">
    <!-- Revenue Chart (Daily) -->
    <div class="lg:col-span-2 bg-white rounded-xl border">
        <div class="px-5 py-4 border-b flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-800">รายได้</h2>
            <div class="flex gap-1" x-data="{ tab: 'daily' }">
                <button @click="tab = 'daily'; document.getElementById('chart-daily').style.display='block'; document.getElementById('chart-monthly').style.display='none';" :class="tab === 'daily' ? 'bg-gray-800 text-white' : 'text-gray-500 hover:bg-gray-100'" class="px-3 py-1 rounded-md text-xs font-medium transition-colors">30 วัน</button>
                <button @click="tab = 'monthly'; document.getElementById('chart-daily').style.display='none'; document.getElementById('chart-monthly').style.display='block';" :class="tab === 'monthly' ? 'bg-gray-800 text-white' : 'text-gray-500 hover:bg-gray-100'" class="px-3 py-1 rounded-md text-xs font-medium transition-colors">12 เดือน</button>
            </div>
        </div>
        <div class="p-5">
            <div id="chart-daily" style="height: 280px;"><canvas id="dailyRevenueChart"></canvas></div>
            <div id="chart-monthly" style="height: 280px; display: none;"><canvas id="monthlyRevenueChart"></canvas></div>
        </div>
    </div>

    <!-- Order Status Distribution -->
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-4 border-b">
            <h2 class="text-sm font-semibold text-gray-800">สถานะคำสั่งซื้อ</h2>
        </div>
        <div class="p-5 flex flex-col items-center">
            <div style="height: 200px; width: 200px;"><canvas id="statusChart"></canvas></div>
            @php
                $statusLabels = [
                    'awaiting_payment' => ['รอชำระ', 'bg-yellow-400'],
                    'paid' => ['ชำระแล้ว', 'bg-blue-400'],
                    'processing' => ['กำลังจัดเตรียม', 'bg-indigo-400'],
                    'shipped' => ['จัดส่งแล้ว', 'bg-purple-400'],
                    'delivered' => ['ส่งสำเร็จ', 'bg-green-400'],
                    'cancelled' => ['ยกเลิก', 'bg-red-400'],
                    'expired' => ['หมดอายุ', 'bg-gray-400'],
                ];
            @endphp
            <div class="mt-4 grid grid-cols-2 gap-x-6 gap-y-1.5 text-xs w-full">
                @foreach($statusLabels as $key => [$label, $color])
                    @if(isset($statusDist[$key]) && $statusDist[$key] > 0)
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full {{ $color }} shrink-0"></span>
                            <span class="text-gray-600">{{ $label }}</span>
                            <span class="ml-auto font-semibold text-gray-800">{{ $statusDist[$key] }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row -->
<div class="grid lg:grid-cols-3 gap-6">
    <!-- Recent Orders -->
    <div class="lg:col-span-2 bg-white rounded-xl border">
        <div class="px-5 py-4 border-b flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-800">คำสั่งซื้อล่าสุด</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-teal-600 hover:underline">ดูทั้งหมด</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500">
                    <tr>
                        <th class="px-5 py-2.5 text-left">หมายเลข</th>
                        <th class="px-5 py-2.5 text-left">ลูกค้า</th>
                        <th class="px-5 py-2.5 text-right">ยอด</th>
                        <th class="px-5 py-2.5 text-center">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-teal-600 hover:underline font-medium">{{ $order->order_number }}</a>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $order->user->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-right font-medium">฿{{ number_format($order->total, 0) }}</td>
                        <td class="px-5 py-3 text-center">
                            @php
                                $colors = [
                                    'pending' => 'bg-gray-100 text-gray-600',
                                    'awaiting_payment' => 'bg-yellow-100 text-yellow-700',
                                    'paid' => 'bg-blue-100 text-blue-700',
                                    'processing' => 'bg-indigo-100 text-indigo-700',
                                    'shipped' => 'bg-purple-100 text-purple-700',
                                    'delivered' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    'expired' => 'bg-gray-100 text-gray-600',
                                ];
                                $labels = [
                                    'pending' => 'รอดำเนินการ',
                                    'awaiting_payment' => 'รอชำระ',
                                    'paid' => 'ชำระแล้ว',
                                    'processing' => 'กำลังจัดเตรียม',
                                    'shipped' => 'จัดส่งแล้ว',
                                    'delivered' => 'ส่งสำเร็จ',
                                    'cancelled' => 'ยกเลิก',
                                    'expired' => 'หมดอายุ',
                                ];
                            @endphp
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-medium {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $labels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">ยังไม่มีคำสั่งซื้อ</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column -->
    <div class="space-y-6">
        <!-- Top Products -->
        <div class="bg-white rounded-xl border">
            <div class="px-5 py-4 border-b flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-800">สินค้าขายดี</h2>
                <a href="{{ route('admin.products.index') }}" class="text-xs text-teal-600 hover:underline">ดูทั้งหมด</a>
            </div>
            <div class="divide-y">
                @foreach($topProducts as $i => $product)
                <div class="px-5 py-3 flex items-center gap-3">
                    <span class="w-5 h-5 rounded-full {{ $i < 3 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500' }} flex items-center justify-center text-[10px] font-bold">{{ $i + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">{{ $product->name }}</p>
                        <p class="text-[10px] text-gray-400">{{ $product->category->name ?? '' }} · สต็อก {{ $product->stock }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-800">{{ number_format($product->sold) }}</p>
                        <p class="text-[10px] text-gray-400">ขายแล้ว</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="bg-white rounded-xl border">
            <div class="px-5 py-4 border-b">
                <h2 class="text-sm font-semibold text-gray-800">รีวิวล่าสุด</h2>
            </div>
            <div class="divide-y">
                @forelse($recentReviews as $review)
                <div class="px-5 py-3">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-xs font-medium text-gray-700 truncate">{{ $review->user->name ?? 'ผู้ใช้' }}</p>
                        <div class="flex gap-0.5 shrink-0">
                            @for($star = 1; $star <= 5; $star++)
                                <svg class="h-3 w-3 {{ $star <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200' }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 truncate">{{ $review->product->name ?? '' }}</p>
                    <p class="text-xs text-gray-400 mt-0.5 line-clamp-2">{{ Str::limit($review->comment, 80) }}</p>
                </div>
                @empty
                <div class="px-5 py-6 text-center text-xs text-gray-400">ยังไม่มีรีวิว</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
    };

    // Daily Revenue Chart
    new Chart(document.getElementById('dailyRevenueChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'รายได้ (฿)',
                data: @json($chartRevenue),
                borderColor: '#0d9488',
                backgroundColor: 'rgba(13, 148, 136, 0.08)',
                fill: true,
                tension: 0.3,
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: '#0d9488',
            }]
        },
        options: {
            ...chartDefaults,
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 }, maxTicksLimit: 10 } },
                y: { grid: { color: '#f3f4f6' }, ticks: { font: { size: 10 }, callback: v => '฿' + v.toLocaleString() } }
            },
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => '฿' + ctx.parsed.y.toLocaleString()
                    }
                }
            }
        }
    });

    // Monthly Revenue Chart
    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'bar',
        data: {
            labels: @json($monthLabels),
            datasets: [{
                label: 'รายได้ (฿)',
                data: @json($monthRevenueData),
                backgroundColor: 'rgba(13, 148, 136, 0.7)',
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            ...chartDefaults,
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                y: { grid: { color: '#f3f4f6' }, ticks: { font: { size: 10 }, callback: v => '฿' + v.toLocaleString() } }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => '฿' + ctx.parsed.y.toLocaleString()
                    }
                }
            }
        }
    });

    // Status Doughnut Chart
    const statusData = @json($statusDist);
    const statusConfig = {
        'awaiting_payment': { label: 'รอชำระ', color: '#facc15' },
        'paid': { label: 'ชำระแล้ว', color: '#60a5fa' },
        'processing': { label: 'กำลังจัดเตรียม', color: '#818cf8' },
        'shipped': { label: 'จัดส่งแล้ว', color: '#a78bfa' },
        'delivered': { label: 'ส่งสำเร็จ', color: '#4ade80' },
        'cancelled': { label: 'ยกเลิก', color: '#f87171' },
        'expired': { label: 'หมดอายุ', color: '#9ca3af' },
    };
    const sLabels = [], sData = [], sColors = [];
    for (const [key, val] of Object.entries(statusData)) {
        if (statusConfig[key] && val > 0) {
            sLabels.push(statusConfig[key].label);
            sData.push(val);
            sColors.push(statusConfig[key].color);
        }
    }

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: sLabels,
            datasets: [{ data: sData, backgroundColor: sColors, borderWidth: 0 }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.label + ': ' + ctx.parsed + ' รายการ'
                    }
                }
            }
        }
    });
});
</script>
@endsection
