<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>พิมพ์ใบปะหน้าพัสดุ ({{ $orders->count() }} ใบ)</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f3f4f6;
            color: #1a1a1a;
        }

        /* ── Print Controls (hidden on print) ── */
        .print-controls {
            position: fixed; top: 0; left: 0; right: 0;
            background: #1f2937; color: #fff;
            padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between;
            z-index: 100; box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }
        .print-controls .info { font-size: 14px; font-weight: 600; }
        .print-controls .info span { color: #6ee7b7; }
        .print-controls .actions { display: flex; gap: 8px; }
        .print-controls button {
            padding: 8px 20px; border: none; border-radius: 8px;
            font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s;
        }
        .btn-print { background: #10b981; color: #fff; }
        .btn-print:hover { background: #059669; }
        .btn-back { background: #374151; color: #d1d5db; }
        .btn-back:hover { background: #4b5563; }

        /* ── Labels Grid ── */
        .labels-wrapper {
            padding: 80px 20px 40px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        /* ── Single Label ── */
        .label {
            width: 150mm; min-height: 100mm;
            background: #fff;
            border: 3px solid #000;
            overflow: hidden;
            page-break-inside: avoid;
            break-inside: avoid;
            display: flex;
            flex-direction: column;
        }

        .label-header {
            background: #000; color: #fff;
            padding: 10px 14px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .label-header .carrier-name {
            font-size: 18px; font-weight: 800; letter-spacing: .5px;
        }
        .label-header .carrier-icon { font-size: 22px; }

        .label-content {
            display: flex;
            flex: 1;
        }

        .left-column {
            flex: 1;
            border-right: 2px dashed #ccc;
        }
        .right-column { flex: 1; }

        .label-section {
            padding: 12px 14px;
            border-bottom: 2px dashed #ccc;
        }
        .label-section:last-child { border-bottom: none; }

        .section-title {
            font-size: 9px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1.5px; color: #888; margin-bottom: 6px;
        }

        .address-block .name {
            font-size: 16px; font-weight: 800; margin-bottom: 2px;
        }
        .address-block .phone {
            font-size: 13px; font-weight: 600; color: #444; margin-bottom: 4px;
        }
        .address-block .addr {
            font-size: 12px; color: #333; line-height: 1.5;
        }
        .address-block .postal {
            display: inline-block; margin-top: 4px;
            font-size: 20px; font-weight: 900; letter-spacing: 2px;
            border: 2px solid #000; padding: 2px 10px; border-radius: 4px;
        }

        .sender-block .name {
            font-size: 12px; font-weight: 700; margin-bottom: 1px;
        }
        .sender-block .addr {
            font-size: 10px; color: #555; line-height: 1.4;
        }

        .barcode-section {
            text-align: center; padding: 10px 14px 14px;
        }
        .barcode-section svg { max-width: 100%; height: auto; }
        .tracking-text {
            font-size: 14px; font-weight: 800; letter-spacing: 2px;
            margin-top: 4px;
        }

        .order-info {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 10px; color: #666;
            margin-bottom: 8px;
        }
        .order-info .order-num {
            font-weight: 700; color: #333; font-size: 11px;
        }

        .product-list {
            margin-top: 6px;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
        }
        .product-item {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 9px; padding: 2px 0;
            line-height: 1.3;
        }
        .product-name {
            color: #333; font-weight: 500; flex: 1; margin-right: 8px;
        }
        .product-qty {
            color: #666; font-weight: 600; min-width: 25px; text-align: right;
        }

        /* ── Print Styles ── */
        @media print {
            .print-controls { display: none !important; }
            body { background: #fff; }
            .labels-wrapper {
                padding: 0;
                display: block;
            }
            .label {
                width: 150mm; min-height: 100mm;
                border: 3px solid #000;
                margin: 0;
                page-break-after: always;
                break-after: page;
            }
            .label:last-child {
                page-break-after: avoid;
                break-after: avoid;
            }
            @page {
                size: 150mm 100mm;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    {{-- Print Controls --}}
    <div class="print-controls">
        <div class="info">
            พิมพ์ใบปะหน้าพัสดุ &mdash; <span>{{ $orders->count() }} ใบ</span>
        </div>
        <div class="actions">
            <button class="btn-back" onclick="window.close()">ปิดหน้านี้</button>
            <button class="btn-print" onclick="window.print()">
                🖨️ พิมพ์ทั้งหมด {{ $orders->count() }} ใบ
            </button>
        </div>
    </div>

    <div class="labels-wrapper">
        @foreach($orders as $order)
        @php
            $address = $order->shipping_address ?? [];
            $recipientName     = $address['name'] ?? ($order->user->name ?? '-');
            $recipientPhone    = $address['phone'] ?? '';
            $recipientAddress  = $address['address'] ?? '';
            $recipientDistrict = $address['district'] ?? '';
            $recipientProvince = $address['province'] ?? '';
            $recipientPostal   = $address['postal_code'] ?? $address['postalCode'] ?? '';
        @endphp
        <div class="label" id="label-{{ $order->id }}">
            {{-- Carrier Header --}}
            <div class="label-header">
                <span class="carrier-name">ไปรษณีย์ไทย EMS</span>
                <span class="carrier-icon">📮</span>
            </div>

            <div class="label-content">
                {{-- Left Column --}}
                <div class="left-column">
                    {{-- Barcode --}}
                    <div class="barcode-section">
                        <svg id="barcode-{{ $order->id }}"></svg>
                        <div class="tracking-text">{{ $order->tracking_number }}</div>
                    </div>

                    {{-- Recipient --}}
                    <div class="label-section">
                        <div class="section-title">ผู้รับ / Receiver</div>
                        <div class="address-block">
                            <div class="name">{{ $recipientName }}</div>
                            @if($recipientPhone)<div class="phone">{{ $recipientPhone }}</div>@endif
                            <div class="addr">
                                {{ $recipientAddress }}<br>
                                {{ $recipientDistrict }} {{ $recipientProvince }}
                            </div>
                            @if($recipientPostal)
                                <div class="postal">{{ $recipientPostal }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Sender --}}
                    <div class="label-section">
                        <div class="section-title">ผู้ส่ง / Sender</div>
                        <div class="sender-block">
                            <div class="name">{{ $senderName }}</div>
                            <div class="addr">
                                {!! nl2br(e($senderAddress)) !!}<br>
                                {{ $senderPhone }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="right-column">
                    <div class="label-section">
                        <div class="section-title">ข้อมูลออเดอร์ / Order Info</div>
                        <div class="order-info">
                            <span class="order-num">{{ $order->order_number }}</span>
                            <span>{{ $order->created_at->format('d/m/Y') }}</span>
                            <span>{{ $order->items->sum('quantity') }} ชิ้น</span>
                        </div>

                        <div class="product-list">
                            @forelse($order->items as $item)
                                <div class="product-item">
                                    <span class="product-name">{{ Str::limit($item->product->name ?? '-', 40) }}</span>
                                    <span class="product-qty">x{{ $item->quantity }}</span>
                                </div>
                            @empty
                                <div class="product-item">ไม่มีข้อมูลสินค้า</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach($orders as $order)
            @if($order->tracking_number)
            try {
                JsBarcode("#barcode-{{ $order->id }}", "{{ $order->tracking_number }}", {
                    format: "CODE128",
                    width: 2,
                    height: 50,
                    displayValue: false,
                    margin: 5,
                });
            } catch(e) { console.warn('Barcode failed for {{ $order->order_number }}:', e); }
            @endif
            @endforeach
        });
    </script>
</body>
</html>
