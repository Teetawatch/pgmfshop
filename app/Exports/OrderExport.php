<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromArray, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
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

        $statusLabels = [
            'pending' => 'รอดำเนินการ',
            'awaiting_payment' => 'รอชำระเงิน',
            'paid' => 'ชำระแล้ว',
            'processing' => 'กำลังจัดเตรียม',
            'shipped' => 'จัดส่งแล้ว',
            'delivered' => 'ส่งสำเร็จ',
            'cancelled' => 'ยกเลิก',
            'expired' => 'ไม่ชำระตามกำหนด',
        ];

        $paymentLabels = [
            'promptpay' => 'PromptPay',
            'bank_transfer' => 'โอนเงิน',
        ];

        $rows = [];

        foreach ($orders as $order) {
            $address = $order->shipping_address;
            $fullAddress = '';
            if ($address) {
                $parts = array_filter([
                    $address['address'] ?? '',
                    $address['district'] ?? '',
                    $address['province'] ?? '',
                    $address['postalCode'] ?? $address['postal_code'] ?? '',
                ]);
                $fullAddress = implode(' ', $parts);
            }

            $items = $order->items;

            if ($items->isEmpty()) {
                $rows[] = [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i'),
                    $address['name'] ?? ($order->user->name ?? '-'),
                    $order->user->email ?? '-',
                    $address['phone'] ?? '',
                    $fullAddress,
                    '-',
                    '-',
                    '-',
                    0,
                    0,
                    0,
                    number_format($order->subtotal, 2),
                    number_format($order->discount, 2),
                    number_format($order->shipping_cost, 2),
                    number_format($order->total, 2),
                    $paymentLabels[$order->payment_method] ?? $order->payment_method ?? '-',
                    $statusLabels[$order->status] ?? $order->status,
                    $order->tracking_number ?? '-',
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
                        $index === 0 ? $order->created_at->format('Y-m-d H:i') : '',
                        $index === 0 ? ($address['name'] ?? ($order->user->name ?? '-')) : '',
                        $index === 0 ? ($order->user->email ?? '-') : '',
                        $index === 0 ? ($address['phone'] ?? '') : '',
                        $index === 0 ? $fullAddress : '',
                        $item->product_name,
                        $size,
                        $color,
                        $item->quantity,
                        number_format($item->price, 2),
                        number_format($item->total, 2),
                        $index === 0 ? number_format($order->subtotal, 2) : '',
                        $index === 0 ? number_format($order->discount, 2) : '',
                        $index === 0 ? number_format($order->shipping_cost, 2) : '',
                        $index === 0 ? number_format($order->total, 2) : '',
                        $index === 0 ? ($paymentLabels[$order->payment_method] ?? $order->payment_method ?? '-') : '',
                        $index === 0 ? ($statusLabels[$order->status] ?? $order->status) : '',
                        $index === 0 ? ($order->tracking_number ?? '-') : '',
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
            'วันที่สั่งซื้อ',
            'ชื่อลูกค้า',
            'อีเมล',
            'เบอร์โทร',
            'ที่อยู่จัดส่ง',
            'สินค้า',
            'ไซส์',
            'สี',
            'จำนวน',
            'ราคาต่อชิ้น',
            'รวมต่อรายการ',
            'ราคาสินค้ารวม',
            'ส่วนลด',
            'ค่าจัดส่ง',
            'ยอดรวมทั้งหมด',
            'วิธีชำระเงิน',
            'สถานะ',
            'เลขพัสดุ',
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
        return 'คำสั่งซื้อ';
    }
}
