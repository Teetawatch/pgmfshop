<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <style>
        
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: 400;
            src: url("{{ resource_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: 700;
            src: url("{{ resource_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'THSarabunNew', sans-serif;
            font-size: 16px;
            color: #333;
             margin: 0;
            padding: 0;
        }

        .receipt-container {
            width: 100%;
            max-width: 100%;
            padding: 20px 15px;
        }

        /* Header */
        .header {
            border-top: 4px solid #1e3a5f;
            padding-top: 15px;
            margin-bottom: 10px;
        }

        .shop-name {
            font-size: 32px;
            font-weight: 700;
            color: #1e3a5f;
            margin-bottom: 2px;
        }

        .shop-address {
            font-size: 16px;
            color: #555;
            line-height: 1.0;
            letter-spacing: normal;
        }

        /* Title */
        .receipt-title {
            font-size: 32px;
            font-weight: 700;
            color: #1e3a5f;
            margin: 15px 0 2px 0;
        }

        /* Info Row */
        .info-row {
            width: 100%;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .info-row table {
            width: 100%;
        }

        .info-row td {
            vertical-align: top;
        }

        .info-label {
            font-size: 16px;
            font-weight: 700;
            color: #1e3a5f;
        }

        .info-value {
            font-size: 13px;
            color: #333;
        }

        /* Customer Info */
        .customer-section {
            margin-bottom: 2px;
        }

        .customer-label {
            font-size: 14px;
            font-weight: 700;
            color: #1e3a5f;
            margin-bottom: 1px;
            line-height: 1.0;
            letter-spacing: normal;
        }

        .customer-name {
            font-size: 13px;
            color: #333;
            margin-bottom: 1px;
            line-height: 1.0;
            letter-spacing: normal;
        }

        .customer-address {
            font-size: 12px;
            color: #555;
            margin-bottom: 1px;
            line-height: 1.0;
            letter-spacing: normal;
        }

        .customer-phone {
            font-size: 12px;
            color: #555;
            margin-top: 0px;
            line-height: 1.0;
            letter-spacing: normal;
        }

       /* =========================
   Items Table (DomPDF Safe)
   ========================= */
.items-table {
    width: 95%;
    border-collapse: collapse;
}

.items-table th,
.items-table td {
    padding: 4px;
    font-size: 14px;
    word-wrap: break-word;
    vertical-align: top;
}

.items-table thead th {
    background-color: #f0f0f0;
    border-top: 2px solid #1e3a5f;
    border-bottom: 1px solid #ccc;
    font-weight: 700;
    color: #1e3a5f;
}

.items-table tbody td {
    border-bottom: 1px solid #ddd;
}

.items-table tfoot td {
    border-top: 2px solid #1e3a5f;
    font-size: 16px;
    font-weight: 700;
}

/* ป้องกันแถวขาดกลางหน้า */
.items-table tr {
    page-break-inside: avoid;
}


/* Body */
.items-table tbody td {
    padding: 4px;
    font-size: 14px;
    border-bottom: 1px solid #e5e5e5;
    vertical-align: top;

    /* สำคัญสำหรับ DomPDF */
    word-break: break-all;
}

/* Footer */
.items-table tfoot td {
    padding: 6px 4px;
    font-size: 15px;
    font-weight: 700;
    border-top: 2px solid #1e3a5f;
}

        /* Summary */
        .summary-row {
            width: 100%;
            border-bottom: 1px solid #e5e5e5;
        }

        .summary-row td {
            padding: 4px 6px;
            font-size: 16px;
        }

        .summary-label {
            text-align: right;
            padding-right: 10px;
        }

        .summary-value {
            text-align: right;
            width: 19%;
        }

        /* Subtotal */
        .subtotal-row {
            border-top: 2px solid #1e3a5f;
        }

        .subtotal-row td {
            padding: 6px 6px;
            font-size: 16px;
            font-weight: 700;
        }

        /* Grand Total */
        .grand-total {
            text-align: center;
            margin-top: 25px;
            padding-top: 10px;
        }

        .grand-total-text {
            font-size: 16px;
            font-weight: 700;
            color: #333;
        }

        .grand-total-amount {
            font-size: 28px;
            font-weight: 700;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        {{-- Header --}}
        <div class="header">
            <div class="shop-name">{{ $shopName }}</div>
            <div class="shop-address">
                {!! $shopAddress !!}<br>
                {{ $shopTaxId }}
            </div>
        </div>

        {{-- Receipt Title --}}
        <div class="receipt-title">ใบเสร็จ</div>

        {{-- Date & Receipt Number --}}
        <div class="info-row">
            <table>
                <tr>
                    <td style="width: 50%;">
                        <div class="info-label">วันที่</div>
                        <div class="info-value">{{ $receiptDate }}</div>
                    </td>
                    <td style="width: 50%;">
                        <div class="info-label">เลขที่ใบเสร็จ</div>
                        <div class="info-value">{{ $receiptNumber }}</div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Customer Info --}}
        <div class="customer-section">
            <div class="customer-label">ชื่อลูกค้า</div>
            <div class="customer-name">ชื่อ {{ $customerName }}</div>
            @if($customerAddress)
                <div class="customer-address">ที่อยู่ {{ $customerAddress }}</div>
            @endif
            @if($customerPhone)
                <div class="customer-phone">โทร {{ $customerPhone }}</div>
            @endif
        </div>

        <br>
        {{-- Items Table --}}
        <table class="items-table" width="100%">
    <thead>
        <tr>
            <th width="8%" align="center">ลำดับ</th>
            <th width="42%" align="left">รายการ</th>
            <th width="10%" align="center">จำนวน</th>
            <th width="20%" align="right">ราคา/หน่วย</th>
            <th width="20%" align="right">รวม</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $index => $item)
        <tr>
            <td align="center">{{ $index + 1 }}</td>
            <td>{{ $item->product_name }}@if(!empty($item->options))<br><small style="color:#999;">@if(!empty($item->options['size']))ไซส์: {{ $item->options['size'] }}@endif @if(!empty($item->options['color']))สี: {{ $item->options['color'] }}@endif</small>@endif</td>
            <td align="center">{{ $item->quantity }}</td>
            <td align="right">{{ number_format($item->price, 2) }}</td>
            <td align="right">{{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach

        {{-- Shipping Cost --}}
        @if($order->shipping_cost > 0)
        <tr>
            <td>&nbsp;</td>
            <td>ค่าส่ง</td>
            <td align="center">1</td>
            <td align="right">{{ number_format($order->shipping_cost, 2) }}</td>
            <td align="right">{{ number_format($order->shipping_cost, 2) }}</td>
        </tr>
        @endif

        {{-- Discount --}}
        @if($order->discount > 0)
        <tr>
            <td>&nbsp;</td>
            <td>ส่วนลด</td>
            <td align="center">1</td>
            <td align="right">-{{ number_format($order->discount, 2) }}</td>
            <td align="right">-{{ number_format($order->discount, 2) }}</td>
        </tr>
        @endif
    </tbody>

    <tfoot>
        <tr>
            <td colspan="3">{{ $bahtText }}</td>
            <td colspan="1" align="right">ยอดรวมทั้งสิ้น</td>
            <td align="right">{{ number_format($order->total, 2) }}</td>
        </tr>
    </tfoot>
</table>
    </div>
</body>
</html>
