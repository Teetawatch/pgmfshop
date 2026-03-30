<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderSummarySheet implements FromArray, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected ?string $status;

    public function __construct(?string $status = null)
    {
        $this->status = $status;
    }

    public function array(): array
    {
        $query = Order::with(['items.variant']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $orders = $query->latest()->get();

        // Collect all items grouped by product -> size -> color
        $summary = [];
        $allSizes = [];
        $allColors = [];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $productName = $item->product_name;
                $size = '-';
                $color = '-';

                if ($item->variant) {
                    $size = $item->variant->size ?: '-';
                    $color = $item->variant->color ?: '-';
                } elseif ($item->options) {
                    $size = $item->options['size'] ?? '-';
                    $color = $item->options['color'] ?? '-';
                }

                if (!isset($summary[$productName])) {
                    $summary[$productName] = [];
                }
                if (!isset($summary[$productName][$size])) {
                    $summary[$productName][$size] = [];
                }
                if (!isset($summary[$productName][$size][$color])) {
                    $summary[$productName][$size][$color] = 0;
                }

                $summary[$productName][$size][$color] += $item->quantity;

                $allSizes[$size] = true;
                $allColors[$color] = true;
            }
        }

        // Define preferred order for sizes and colors
        $sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '2XL', '3XL'];
        $sizes = array_keys($allSizes);
        usort($sizes, function ($a, $b) use ($sizeOrder) {
            $posA = array_search(strtoupper($a), array_map('strtoupper', $sizeOrder));
            $posB = array_search(strtoupper($b), array_map('strtoupper', $sizeOrder));
            if ($posA === false) $posA = 999;
            if ($posB === false) $posB = 999;
            return $posA - $posB;
        });

        $colors = array_keys($allColors);
        sort($colors);

        $rows = [];

        foreach ($summary as $productName => $sizeData) {
            $productTotal = 0;
            $firstRow = true;

            foreach ($sizes as $size) {
                if (!isset($sizeData[$size])) continue;

                foreach ($colors as $color) {
                    if (!isset($sizeData[$size][$color])) continue;

                    $qty = $sizeData[$size][$color];
                    $productTotal += $qty;

                    $rows[] = [
                        $firstRow ? $productName : '',
                        $size,
                        $color,
                        $qty,
                    ];
                    $firstRow = false;
                }
            }

            // Subtotal row per product
            $rows[] = [
                '',
                '',
                'รวม ' . $productName,
                $productTotal,
            ];

            // Empty separator row
            $rows[] = ['', '', '', ''];
        }

        // Grand summary: by size
        $rows[] = ['', '', '', ''];
        $rows[] = ['สรุปตามไซส์', '', '', ''];

        $grandTotal = 0;
        foreach ($sizes as $size) {
            $sizeTotal = 0;
            foreach ($summary as $sizeData) {
                if (isset($sizeData[$size])) {
                    foreach ($sizeData[$size] as $qty) {
                        $sizeTotal += $qty;
                    }
                }
            }
            if ($sizeTotal > 0) {
                $rows[] = ['', $size, '', $sizeTotal];
                $grandTotal += $sizeTotal;
            }
        }
        $rows[] = ['', '', 'รวมทุกไซส์', $grandTotal];

        // Grand summary: by color
        $rows[] = ['', '', '', ''];
        $rows[] = ['สรุปตามสี', '', '', ''];

        $grandTotal = 0;
        foreach ($colors as $color) {
            $colorTotal = 0;
            foreach ($summary as $sizeData) {
                foreach ($sizeData as $colorData) {
                    if (isset($colorData[$color])) {
                        $colorTotal += $colorData[$color];
                    }
                }
            }
            if ($colorTotal > 0) {
                $rows[] = ['', '', $color, $colorTotal];
                $grandTotal += $colorTotal;
            }
        }
        $rows[] = ['', '', 'รวมทุกสี', $grandTotal];

        // Grand summary: by size x color
        $rows[] = ['', '', '', ''];
        $rows[] = ['สรุปตามไซส์ x สี', '', '', ''];

        foreach ($sizes as $size) {
            foreach ($colors as $color) {
                $total = 0;
                foreach ($summary as $sizeData) {
                    if (isset($sizeData[$size][$color])) {
                        $total += $sizeData[$size][$color];
                    }
                }
                if ($total > 0) {
                    $rows[] = ['', $size, $color, $total];
                }
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'สินค้า',
            'ไซส์',
            'สี',
            'จำนวน (ตัว)',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = $sheet->getHighestRow();
        $styles = [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FCE4D6'],
                ],
            ],
        ];

        // Bold the summary section headers and subtotal rows
        for ($row = 2; $row <= $lastRow; $row++) {
            $cellA = $sheet->getCell("A{$row}")->getValue();
            $cellC = $sheet->getCell("C{$row}")->getValue();

            // Section headers
            if (in_array($cellA, ['สรุปตามไซส์', 'สรุปตามสี', 'สรุปตามไซส์ x สี'])) {
                $styles[$row] = [
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1F4E79']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'DAEEF3'],
                    ],
                ];
            }

            // Subtotal rows (รวม...)
            if ($cellC && (str_starts_with($cellC, 'รวม '))) {
                $styles[$row] = [
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFF2CC'],
                    ],
                ];
            }

            // Grand total rows
            if ($cellC && in_array($cellC, ['รวมทุกไซส์', 'รวมทุกสี'])) {
                $styles[$row] = [
                    'font' => ['bold' => true, 'size' => 11],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E2EFDA'],
                    ],
                ];
            }
        }

        return $styles;
    }

    public function title(): string
    {
        return 'สรุปจำนวนสินค้า';
    }
}
