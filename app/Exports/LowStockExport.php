<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LowStockExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected int $threshold;

    public function __construct(int $threshold = 20)
    {
        $this->threshold = $threshold;
    }

    public function collection()
    {
        return Product::with('category')
            ->where('is_active', true)
            ->where('stock', '<=', $this->threshold)
            ->orderBy('stock', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'รหัสสินค้า',
            'ชื่อสินค้า',
            'หมวดหมู่',
            'สต็อกคงเหลือ',
            'ขายแล้ว',
            'ราคา (บาท)',
            'มูลค่าคงเหลือ (บาท)',
            'สถานะ',
        ];
    }

    public function map($product): array
    {
        $status = match (true) {
            $product->stock === 0 => 'หมด',
            $product->stock <= 5 => 'วิกฤต',
            default => 'ต่ำ',
        };

        return [
            $product->id,
            $product->name,
            $product->category->name ?? '-',
            $product->stock,
            $product->sold,
            number_format($product->price, 2),
            number_format($product->stock * $product->price, 2),
            $status,
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
        return 'สต็อกต่ำ';
    }
}
