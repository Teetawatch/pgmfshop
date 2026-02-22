<?php

namespace App\Exports;

use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BestSellingExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected Carbon $startDate;
    protected Carbon $endDate;
    protected int $limit;

    public function __construct(Carbon $startDate, Carbon $endDate, int $limit = 50)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->limit = $limit;
    }

    public function collection()
    {
        $items = OrderItem::select(
                'order_items.product_id',
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as order_count'),
                DB::raw('AVG(order_items.price) as avg_price')
            )
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereNotIn('orders.status', ['cancelled', 'expired'])
            ->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('total_sold')
            ->limit($this->limit)
            ->get();

        $productIds = $items->pluck('product_id')->toArray();
        $products = Product::with('category')->whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($items as $item) {
            $p = $products[$item->product_id] ?? null;
            $item->category_name = $p?->category?->name ?? '-';
            $item->current_stock = $p?->stock ?? 0;
        }

        return $items;
    }

    public function headings(): array
    {
        return [
            '#',
            'ชื่อสินค้า',
            'หมวดหมู่',
            'จำนวนขาย',
            'ยอดขาย (บาท)',
            'จำนวนออเดอร์',
            'ราคาเฉลี่ย (บาท)',
            'สต็อกคงเหลือ',
        ];
    }

    public function map($item): array
    {
        static $rank = 0;
        $rank++;

        return [
            $rank,
            $item->product_name,
            $item->category_name,
            $item->total_sold,
            number_format($item->total_revenue, 2),
            $item->order_count,
            number_format($item->avg_price, 2),
            $item->current_stock,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }

    public function title(): string
    {
        return 'สินค้าขายดี';
    }
}
