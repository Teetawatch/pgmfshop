<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderDetailSheet implements FromArray, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected ?string $status;

    public function __construct(?string $status = null)
    {
        $this->status = $status;
    }

    public function array(): array
    {
        $query = Order::with(['user', 'items.variant']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $orders = $query->latest()->get();

        $rows = [];

        foreach ($orders as $order) {
            $address = $order->shipping_address;
            $customerName = $address['name'] ?? ($order->user->name ?? '-');

            $items = $order->items;

            if ($items->isEmpty()) {
                $rows[] = [
                    $order->order_number,
                    $customerName,
                    '-',
                    '-',
                    '-',
                    0,
                ];
            } else {
                foreach ($items as $index => $item) {
                    $size = '-';
                    $color = '-';

                    if ($item->variant) {
                        $size = $item->variant->size ?: '-';
                        $color = $item->variant->color ?: '-';
                    } elseif ($item->options) {
                        $size = $item->options['size'] ?? '-';
                        $color = $item->options['color'] ?? '-';
                    }

                    $rows[] = [
                        $index === 0 ? $order->order_number : '',
                        $index === 0 ? $customerName : '',
                        $item->product_name,
                        $size,
                        $color,
                        $item->quantity,
                    ];
                }
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'เลขคำสั่งซื้อ',
            'ชื่อ - นามสกุล',
            'สินค้า',
            'ไซส์',
            'สี',
            'จำนวน',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9EAD3'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'รายละเอียดคำสั่งซื้อ';
    }
}
