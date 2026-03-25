<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected Carbon $startDate;
    protected Carbon $endDate;

    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Order::with('items', 'user')
            ->whereNotIn('status', ['cancelled', 'expired'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'เลขคำสั่งซื้อ',
            'วันที่',
            'ลูกค้า',
            'จำนวนสินค้า',
            'ราคาสินค้า',
            'ส่วนลด',
            'ค่าจัดส่ง',
            'ยอดรวม',
            'วิธีชำระ',
            'สถานะ',
        ];
    }

    public function map($order): array
    {
        $statusLabels = [
            'pending' => 'รอดำเนินการ',
            'awaiting_payment' => 'รอชำระเงิน',
            'paid' => 'ชำระแล้ว',
            'processing' => 'กำลังจัดเตรียม',
            'shipped' => 'จัดส่งแล้ว',
            'delivered' => 'ส่งสำเร็จ',
        ];

        $paymentLabels = [
            'promptpay' => 'PromptPay',
            'bank_transfer' => 'โอนเงิน',
        ];

        return [
            $order->order_number,
            $order->created_at->format('Y-m-d H:i'),
            $order->user->name ?? '-',
            $order->items->sum('quantity'),
            number_format($order->subtotal, 2),
            number_format($order->discount, 2),
            number_format($order->shipping_cost, 2),
            number_format($order->total, 2),
            $paymentLabels[$order->payment_method] ?? $order->payment_method,
            $statusLabels[$order->status] ?? $order->status,
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
        return 'รายงานยอดขาย';
    }
}
