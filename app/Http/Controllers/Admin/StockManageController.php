<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StockManageController extends Controller
{
    /**
     * Stock overview dashboard
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filters
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            match ($request->status) {
                'out' => $query->where('stock', 0),
                'low' => $query->where('stock', '>', 0)->where('stock', '<=', 10),
                'warning' => $query->where('stock', '>', 10)->where('stock', '<=', 30),
                'ok' => $query->where('stock', '>', 30),
                default => null,
            };
        }

        // Sort
        $sortField = $request->input('sort', 'stock');
        $sortDir = $request->input('dir', 'asc');
        $allowedSorts = ['stock', 'sold', 'name', 'updated_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir === 'desc' ? 'desc' : 'asc');
        }

        $products = $query->paginate(30)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();

        // Summary stats
        $stats = [
            'total' => Product::count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'low_stock' => Product::where('stock', '>', 0)->where('stock', '<=', 10)->count(),
            'warning_stock' => Product::where('stock', '>', 10)->where('stock', '<=', 30)->count(),
            'total_value' => Product::selectRaw('SUM(stock * price) as val')->value('val') ?? 0,
        ];

        return view('admin.stock.index', compact('products', 'categories', 'stats'));
    }

    /**
     * Show stock detail for a single product
     */
    public function show(Product $product)
    {
        $product->load(['category', 'variants']);
        $movements = $product->stockMovements()
            ->with(['user', 'variant'])
            ->latest()
            ->paginate(20);

        return view('admin.stock.show', compact('product', 'movements'));
    }

    /**
     * Quick adjust stock for a single product (AJAX-friendly)
     */
    public function adjust(Request $request, Product $product)
    {
        $request->validate([
            'type' => 'required|in:in,out,adjust',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ], [
            'type.required' => 'กรุณาเลือกประเภท',
            'quantity.required' => 'กรุณาระบุจำนวน',
            'quantity.min' => 'จำนวนต้องมากกว่า 0',
            'reason.required' => 'กรุณาระบุเหตุผล',
        ]);

        $qty = (int) $request->quantity;

        if ($request->type === 'out') {
            if ($qty > $product->stock) {
                return back()->with('error', "สต็อกไม่เพียงพอ (มี {$product->stock} ชิ้น)");
            }
            $qty = -$qty;
        } elseif ($request->type === 'adjust') {
            // Set to exact amount
            $qty = $request->quantity - $product->stock;
        }

        $product->adjustStock(
            $qty,
            $request->type,
            $request->reason,
            StockMovement::REF_MANUAL
        );

        return back()->with('success', "ปรับสต็อก {$product->name} สำเร็จ (ปัจจุบัน: {$product->stock} ชิ้น)");
    }

    /**
     * Bulk adjust form
     */
    public function bulkForm(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->orderBy('name')->paginate(50)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.stock.bulk', compact('products', 'categories'));
    }

    /**
     * Process bulk stock adjustments
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'adjustments' => 'required|array|min:1',
            'adjustments.*.product_id' => 'required|exists:products,id',
            'adjustments.*.quantity' => 'required|integer',
            'adjustments.*.type' => 'required|in:in,out,adjust',
            'bulk_reason' => 'required|string|max:255',
        ], [
            'adjustments.required' => 'กรุณาเลือกสินค้าอย่างน้อย 1 รายการ',
            'bulk_reason.required' => 'กรุณาระบุเหตุผล',
        ]);

        $count = 0;
        $reason = $request->bulk_reason;

        foreach ($request->adjustments as $adj) {
            if (empty($adj['quantity']) || $adj['quantity'] == 0) continue;

            $product = Product::find($adj['product_id']);
            if (!$product) continue;

            $qty = (int) $adj['quantity'];

            if ($adj['type'] === 'out') {
                $qty = -abs($qty);
                if (abs($qty) > $product->stock) continue;
            } elseif ($adj['type'] === 'adjust') {
                $qty = $adj['quantity'] - $product->stock;
                if ($qty === 0) continue;
            }

            $product->adjustStock($qty, $adj['type'], $reason, StockMovement::REF_MANUAL);
            $count++;
        }

        return redirect()->route('admin.stock.index')->with('success', "ปรับสต็อกสำเร็จ {$count} รายการ");
    }

    /**
     * Stock movement history (all products)
     */
    public function history(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('reference_type')) {
            $query->where('reference_type', $request->reference_type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query->latest()->paginate(30)->withQueryString();

        return view('admin.stock.history', compact('movements'));
    }

    /**
     * Export stock report as CSV
     */
    public function export(Request $request): StreamedResponse
    {
        $products = Product::with('category')->orderBy('name')->get();

        return response()->streamDownload(function () use ($products) {
            $handle = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['รหัส', 'ชื่อสินค้า', 'หมวดหมู่', 'ราคา', 'สต็อก', 'ขายแล้ว', 'มูลค่าสต็อก', 'สถานะ']);

            foreach ($products as $p) {
                $status = match (true) {
                    $p->stock === 0 => 'หมด',
                    $p->stock <= 10 => 'วิกฤต',
                    $p->stock <= 30 => 'เตือน',
                    default => 'ปกติ',
                };

                fputcsv($handle, [
                    $p->id,
                    $p->name,
                    $p->category->name ?? '-',
                    $p->price,
                    $p->stock,
                    $p->sold,
                    $p->stock * $p->price,
                    $status,
                ]);
            }

            fclose($handle);
        }, 'stock-report-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
