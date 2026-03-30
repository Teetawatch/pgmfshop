<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OrderExport implements WithMultipleSheets
{
    protected ?string $status;

    public function __construct(?string $status = null)
    {
        $this->status = $status;
    }

    public function sheets(): array
    {
        return [
            new OrderDetailSheet($this->status),
            new OrderSummarySheet($this->status),
        ];
    }
}
