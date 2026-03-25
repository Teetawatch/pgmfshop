<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $todayRevenue = Order::whereNotIn('status', ['cancelled', 'expired'])
            ->whereDate('created_at', $now->toDateString())
            ->sum('total');
        $monthRevenue = Order::whereNotIn('status', ['cancelled', 'expired'])
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total');

        $stats = [
            'total_revenue' => Order::whereNotIn('status', ['cancelled', 'expired'])->sum('total'),
            'today_revenue' => $todayRevenue,
            'month_revenue' => $monthRevenue,
            'total_orders' => Order::count(),
            'today_orders' => Order::whereDate('created_at', $now->toDateString())->count(),
            'total_products' => Product::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'new_customers_month' => User::where('role', 'customer')
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count(),
            'pending_orders' => Order::whereIn('status', ['pending', 'awaiting_payment'])->count(),
            'low_stock' => Product::where('stock', '<', 20)->where('is_active', true)->count(),
            'active_coupons' => Coupon::where('is_active', true)->where('end_date', '>=', now())->count(),
            'total_reviews' => Review::count(),
            'avg_rating' => round(Review::avg('rating') ?? 0, 1),
        ];

        // Daily revenue for the last 30 days
        $dailyRevenue = Order::whereNotIn('status', ['cancelled', 'expired'])
            ->where('created_at', '>=', $now->copy()->subDays(29)->startOfDay())
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartRevenue = [];
        $chartOrders = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->format('Y-m-d');
            $chartLabels[] = $now->copy()->subDays($i)->format('d/m');
            $chartRevenue[] = (float) ($dailyRevenue[$date]->revenue ?? 0);
            $chartOrders[] = (int) ($dailyRevenue[$date]->orders ?? 0);
        }

        // Monthly revenue for the last 12 months
        $monthlyRevenue = Order::whereNotIn('status', ['cancelled', 'expired'])
            ->where('created_at', '>=', $now->copy()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $monthlyMap = [];
        foreach ($monthlyRevenue as $m) {
            $key = $m->year . '-' . str_pad($m->month, 2, '0', STR_PAD_LEFT);
            $monthlyMap[$key] = $m;
        }

        $monthLabels = [];
        $monthRevenueData = [];
        $monthOrdersData = [];
        $thaiMonths = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        for ($i = 11; $i >= 0; $i--) {
            $d = $now->copy()->subMonths($i);
            $key = $d->format('Y-m');
            $monthLabels[] = $thaiMonths[(int)$d->format('m')] . ' ' . ($d->format('Y') + 543 - 2500);
            $monthRevenueData[] = (float) ($monthlyMap[$key]->revenue ?? 0);
            $monthOrdersData[] = (int) ($monthlyMap[$key]->orders ?? 0);
        }

        // Order status distribution
        $statusDist = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $recentOrders = Order::with('user')->latest()->limit(10)->get();
        $topProducts = Product::with('category')->orderByDesc('sold')->limit(5)->get();
        $recentReviews = Review::with(['user', 'product'])->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'recentOrders', 'topProducts', 'recentReviews',
            'chartLabels', 'chartRevenue', 'chartOrders',
            'monthLabels', 'monthRevenueData', 'monthOrdersData',
            'statusDist'
        ));
    }
}
