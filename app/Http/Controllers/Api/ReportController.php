<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Coupon;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dashboard()
    {
        $totalRevenue = Order::whereNotIn('status', ['cancelled'])->sum('total');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $pendingOrders = Order::whereIn('status', ['pending', 'awaiting_payment'])->count();

        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $topProducts = Product::orderBy('sold', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'sold', 'price', 'stock']);

        $lowStockProducts = Product::where('stock', '<', 20)
            ->where('is_active', true)
            ->orderBy('stock')
            ->limit(10)
            ->get(['id', 'name', 'stock', 'sold']);

        return response()->json([
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'total_products' => $totalProducts,
            'total_customers' => $totalCustomers,
            'pending_orders' => $pendingOrders,
            'orders_by_status' => $ordersByStatus,
            'top_products' => $topProducts,
            'low_stock_products' => $lowStockProducts,
        ]);
    }

    public function salesReport(Request $request)
    {
        $request->validate([
            'period' => 'in:daily,weekly,monthly,yearly',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $period = $request->get('period', 'monthly');
        $startDate = $request->get('start_date', now()->subMonths(6)->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfDay());

        $dateFormat = match ($period) {
            'daily' => '%Y-%m-%d',
            'weekly' => '%Y-%u',
            'monthly' => '%Y-%m',
            'yearly' => '%Y',
        };

        $sales = Order::whereNotIn('status', ['cancelled'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period"),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(discount) as total_discount'),
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $categorySales = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotIn('orders.status', ['cancelled'])
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'categories.name',
                DB::raw('SUM(order_items.total) as revenue'),
                DB::raw('SUM(order_items.quantity) as quantity'),
            )
            ->groupBy('categories.name')
            ->orderByDesc('revenue')
            ->get();

        return response()->json([
            'sales' => $sales,
            'category_sales' => $categorySales,
            'summary' => [
                'total_revenue' => $sales->sum('revenue'),
                'total_orders' => $sales->sum('orders'),
                'avg_order_value' => $sales->sum('orders') > 0
                    ? round($sales->sum('revenue') / $sales->sum('orders'), 2)
                    : 0,
            ],
        ]);
    }
}
