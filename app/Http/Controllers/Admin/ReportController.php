<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Exports\SalesReportExport;
use App\Exports\BestSellingExport;
use App\Exports\LowStockExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    // ─── Sales Report ───
    public function sales(Request $request)
    {
        $period = $request->get('period', '30days');
        $startDate = $this->getStartDate($period, $request);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : now()->endOfDay();

        $orders = Order::whereNotIn('status', ['cancelled', 'expired'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        $summary = [
            'total_revenue' => (clone $orders)->sum('total'),
            'total_orders' => (clone $orders)->count(),
            'total_items' => OrderItem::whereIn('order_id', (clone $orders)->pluck('id'))->sum('quantity'),
            'avg_order_value' => (clone $orders)->avg('total') ?? 0,
            'total_shipping' => (clone $orders)->sum('shipping_cost'),
            'total_discount' => (clone $orders)->sum('discount'),
        ];

        // Daily breakdown
        $dailyData = (clone $orders)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(discount) as discount'),
                DB::raw('SUM(shipping_cost) as shipping')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Payment method breakdown
        $paymentBreakdown = (clone $orders)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('payment_method')
            ->get();

        // Status breakdown
        $statusBreakdown = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('status')
            ->get();

        // Chart data
        $chartLabels = [];
        $chartRevenue = [];
        $chartOrders = [];
        $dailyMap = $dailyData->keyBy('date');

        $current = $startDate->copy();
        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            $chartLabels[] = $current->format('d/m');
            $chartRevenue[] = (float) ($dailyMap[$dateStr]->revenue ?? 0);
            $chartOrders[] = (int) ($dailyMap[$dateStr]->orders ?? 0);
            $current->addDay();
        }

        return view('admin.reports.sales', compact(
            'summary', 'dailyData', 'paymentBreakdown', 'statusBreakdown',
            'chartLabels', 'chartRevenue', 'chartOrders',
            'period', 'startDate', 'endDate'
        ));
    }

    // ─── Best-selling Products ───
    public function bestSelling(Request $request)
    {
        $period = $request->get('period', '30days');
        $startDate = $this->getStartDate($period, $request);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : now()->endOfDay();
        $categoryId = $request->get('category');
        $limit = $request->get('limit', 50);

        $query = OrderItem::select(
                'order_items.product_id',
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as order_count'),
                DB::raw('AVG(order_items.price) as avg_price')
            )
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereNotIn('orders.status', ['cancelled', 'expired'])
            ->whereBetween('orders.created_at', [$startDate, $endDate]);

        if ($categoryId) {
            $query->join('products', 'products.id', '=', 'order_items.product_id')
                  ->where('products.category_id', $categoryId);
        }

        $products = $query->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();

        // Enrich with product details
        $productIds = $products->pluck('product_id')->toArray();
        $productDetails = Product::with('category')->whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($products as $p) {
            $detail = $productDetails[$p->product_id] ?? null;
            $p->category_name = $detail?->category?->name ?? '-';
            $p->current_stock = $detail?->stock ?? 0;
            $p->image = $detail && is_array($detail->images) ? ($detail->images[0] ?? '') : '';
            $p->slug = $detail?->slug ?? '';
            $p->rating = $detail?->rating ?? 0;
        }

        // Summary
        $summary = [
            'total_products_sold' => $products->sum('total_sold'),
            'total_revenue' => $products->sum('total_revenue'),
            'unique_products' => $products->count(),
        ];

        // Chart: top 10
        $top10 = $products->take(10);
        $chartLabels = $top10->pluck('product_name')->map(fn($n) => mb_substr($n, 0, 20))->toArray();
        $chartSold = $top10->pluck('total_sold')->toArray();
        $chartRevenue = $top10->pluck('total_revenue')->toArray();

        $categories = Category::orderBy('name')->get();

        return view('admin.reports.best-selling', compact(
            'products', 'summary', 'categories',
            'chartLabels', 'chartSold', 'chartRevenue',
            'period', 'startDate', 'endDate', 'categoryId', 'limit'
        ));
    }

    // ─── Low Stock Report ───
    public function lowStock(Request $request)
    {
        $threshold = $request->get('threshold', 20);
        $categoryId = $request->get('category');
        $sortBy = $request->get('sort', 'stock_asc');

        $query = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '<=', $threshold);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        switch ($sortBy) {
            case 'stock_asc': $query->orderBy('stock', 'asc'); break;
            case 'stock_desc': $query->orderBy('stock', 'desc'); break;
            case 'sold_desc': $query->orderBy('sold', 'desc'); break;
            case 'name_asc': $query->orderBy('name', 'asc'); break;
        }

        $products = $query->get();

        $summary = [
            'out_of_stock' => $products->where('stock', 0)->count(),
            'critical' => $products->where('stock', '>', 0)->where('stock', '<=', 5)->count(),
            'low' => $products->where('stock', '>', 5)->where('stock', '<=', $threshold)->count(),
            'total' => $products->count(),
            'total_value' => $products->sum(fn($p) => $p->stock * $p->price),
        ];

        // Category breakdown
        $categoryBreakdown = $products->groupBy(fn($p) => $p->category->name ?? 'ไม่ระบุ')
            ->map(fn($items) => [
                'count' => $items->count(),
                'out_of_stock' => $items->where('stock', 0)->count(),
            ])
            ->sortByDesc('count');

        $categories = Category::orderBy('name')->get();

        return view('admin.reports.low-stock', compact(
            'products', 'summary', 'categoryBreakdown', 'categories',
            'threshold', 'categoryId', 'sortBy'
        ));
    }

    // ─── Exports ───
    public function exportSalesPdf(Request $request)
    {
        $period = $request->get('period', '30days');
        $startDate = $this->getStartDate($period, $request);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : now()->endOfDay();

        $orders = Order::with('items')
            ->whereNotIn('status', ['cancelled', 'expired'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = [
            'total_revenue' => $orders->sum('total'),
            'total_orders' => $orders->count(),
            'total_items' => $orders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value' => $orders->avg('total') ?? 0,
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.sales', compact('orders', 'summary', 'startDate', 'endDate'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('sales-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportSalesExcel(Request $request)
    {
        $period = $request->get('period', '30days');
        $startDate = $this->getStartDate($period, $request);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : now()->endOfDay();

        return Excel::download(
            new SalesReportExport($startDate, $endDate),
            'sales-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportBestSellingPdf(Request $request)
    {
        $period = $request->get('period', '30days');
        $startDate = $this->getStartDate($period, $request);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : now()->endOfDay();
        $limit = $request->get('limit', 50);

        $products = OrderItem::select(
                'order_items.product_id',
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as order_count')
            )
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereNotIn('orders.status', ['cancelled', 'expired'])
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.best-selling', compact('products', 'startDate', 'endDate'));

        return $pdf->download('best-selling-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportBestSellingExcel(Request $request)
    {
        $period = $request->get('period', '30days');
        $startDate = $this->getStartDate($period, $request);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : now()->endOfDay();
        $limit = $request->get('limit', 50);

        return Excel::download(
            new BestSellingExport($startDate, $endDate, $limit),
            'best-selling-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportLowStockPdf(Request $request)
    {
        $threshold = $request->get('threshold', 20);

        $products = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '<=', $threshold)
            ->orderBy('stock', 'asc')
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.low-stock', compact('products', 'threshold'));

        return $pdf->download('low-stock-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportLowStockExcel(Request $request)
    {
        $threshold = $request->get('threshold', 20);

        return Excel::download(
            new LowStockExport($threshold),
            'low-stock-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // ─── Helper ───
    private function getStartDate(string $period, Request $request): Carbon
    {
        return match ($period) {
            '7days' => now()->subDays(6)->startOfDay(),
            '30days' => now()->subDays(29)->startOfDay(),
            '90days' => now()->subDays(89)->startOfDay(),
            'this_month' => now()->startOfMonth(),
            'last_month' => now()->subMonth()->startOfMonth(),
            'this_year' => now()->startOfYear(),
            'custom' => Carbon::parse($request->get('start_date', now()->subDays(29)))->startOfDay(),
            default => now()->subDays(29)->startOfDay(),
        };
    }
}
