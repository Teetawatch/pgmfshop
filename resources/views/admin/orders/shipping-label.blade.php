<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบปะหน้าพัสดุ - {{ $order->order_number }}</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #e5e7eb;
            color: #1a1a1a;
        }

        /* ── Print Controls ── */
        .print-controls {
            position: fixed; top: 0; left: 0; right: 0;
            background: #111827; color: #fff;
            padding: 10px 24px;
            display: flex; align-items: center; justify-content: space-between;
            z-index: 100; box-shadow: 0 2px 10px rgba(0,0,0,.3);
        }
        .print-controls .info { font-size: 13px; font-weight: 600; }
        .print-controls .info span { color: #6ee7b7; font-size: 14px; }
        .print-controls .actions { display: flex; gap: 8px; }
        .print-controls button {
            padding: 7px 18px; border: none; border-radius: 7px;
            font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s;
        }
        .btn-print { background: #10b981; color: #fff; }
        .btn-print:hover { background: #059669; }
        .btn-back { background: #374151; color: #d1d5db; }
        .btn-back:hover { background: #4b5563; }

        /* ── Label Wrapper ── */
        .label-wrapper {
            display: flex; justify-content: center; align-items: flex-start;
            padding: 72px 20px 40px;
            min-height: 100vh;
        }

        /* ── Label: Landscape 130 × 76 mm ── */
        .label {
            width: 130mm;
            height: 76mm;
            background: #fff;
            border: 2px solid #000;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,.15);
        }

        /* ── Top Header Bar ── */
        .label-header {
            background: #000; color: #fff;
            padding: 5px 10px;
            display: flex; align-items: center; justify-content: space-between;
            flex-shrink: 0;
        }
        .header-left {
            display: flex; align-items: center; gap: 6px;
        }
        .carrier-name {
            font-size: 13px; font-weight: 900; letter-spacing: .5px;
        }
        .carrier-badge {
            background: #fff; color: #000;
            font-size: 8px; font-weight: 800; padding: 1px 5px;
            border-radius: 3px; letter-spacing: .5px;
        }
        .header-right {
            text-align: right;
        }
        .order-number {
            font-size: 9px; font-weight: 700; color: #d1fae5; letter-spacing: .5px;
        }
        .order-date {
            font-size: 8px; color: #9ca3af; margin-top: 1px;
        }

        /* ── Body: 2 columns ── */
        .label-body {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* ── Left Column: Barcode + Recipient ── */
        .col-left {
            width: 58mm;
            border-right: 1.5px dashed #999;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .barcode-wrap {
            padding: 6px 8px 4px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }
        .barcode-wrap svg { width: 100%; height: auto; display: block; }
        .tracking-number {
            font-size: 10px; font-weight: 800; letter-spacing: 1.5px;
            margin-top: 2px; font-family: 'Courier New', monospace;
        }
        .no-tracking {
            padding: 10px 8px;
            font-size: 9px; color: #9ca3af; font-style: italic; text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }

        .recipient-wrap {
            padding: 7px 9px;
            flex: 1;
        }
        .section-label {
            font-size: 7px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 1.2px; color: #9ca3af; margin-bottom: 4px;
        }
        .recipient-name {
            font-size: 13px; font-weight: 900; line-height: 1.2; margin-bottom: 2px;
        }
        .recipient-phone {
            font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 4px;
        }
        .recipient-addr {
            font-size: 9px; color: #4b5563; line-height: 1.5;
        }
        .postal-code {
            display: inline-block; margin-top: 5px;
            font-size: 15px; font-weight: 900; letter-spacing: 2px;
            border: 2px solid #000; padding: 1px 8px; border-radius: 3px;
        }

        /* ── Right Column: Products ── */
        .col-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .products-header {
            padding: 6px 9px 4px;
            border-bottom: 1px solid #e5e7eb;
            display: flex; align-items: center; justify-content: space-between;
        }
        .products-title {
            font-size: 7px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 1.2px; color: #9ca3af;
        }
        .items-count {
            font-size: 9px; font-weight: 700; color: #374151;
            background: #f3f4f6; padding: 1px 6px; border-radius: 10px;
        }

        .products-list {
            padding: 5px 9px;
            flex: 1;
            overflow: hidden;
        }
        .product-row {
            padding: 4px 0;
            border-bottom: 1px dotted #e5e7eb;
        }
        .product-row:last-child { border-bottom: none; }
        .product-row-top {
            display: flex; justify-content: space-between; align-items: flex-start;
            gap: 4px;
        }
        .product-title {
            font-size: 9px; font-weight: 700; color: #111827;
            line-height: 1.3; flex: 1;
        }
        .product-qty-badge {
            font-size: 9px; font-weight: 800; color: #fff;
            background: #111827; border-radius: 3px;
            padding: 0px 5px; white-space: nowrap; flex-shrink: 0;
        }
        .product-variant {
            margin-top: 2px;
            font-size: 8px; color: #6b7280;
            display: flex; align-items: center; gap: 3px;
        }
        .variant-chip {
            background: #f3f4f6; border: 1px solid #e5e7eb;
            border-radius: 3px; padding: 0 4px;
            font-size: 7.5px; font-weight: 600; color: #374151;
        }

        /* ── Bottom Footer Bar ── */
        .label-footer {
            background: #f9fafb;
            border-top: 1.5px solid #e5e7eb;
            padding: 3px 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .footer-brand {
            font-size: 7.5px; font-weight: 700; color: #6b7280; letter-spacing: .3px;
        }

        /* ── Print Styles ── */
        @media print {
            .print-controls { display: none !important; }
            body { background: #fff; }
            .label-wrapper { padding: 0; margin: 0; min-height: unset; }
            .label {
                width: 130mm; height: 76mm;
                border: 1.5px solid #000;
                box-shadow: none;
                margin: 0;
            }
            @page {
                size: 130mm 76mm landscape;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    {{-- Print Controls --}}
    <div class="print-controls">
        <div class="info">
            ใบปะหน้าพัสดุ &mdash; <span>{{ $order->order_number }}</span>
        </div>
        <div class="actions">
            <button class="btn-back" onclick="window.close()">✕ ปิด</button>
            <button class="btn-print" onclick="window.print()">🖨️ พิมพ์</button>
        </div>
    </div>

    {{-- Label --}}
    <div class="label-wrapper">
        <div class="label">

            {{-- Header --}}
            <div class="label-header">
                <div class="header-left">
                    <span class="carrier-name">{{ $carrierName }}</span>
                </div>
                <div class="header-right">
                    <div class="order-number">{{ $order->order_number }}</div>
                    <div class="order-date">{{ $order->created_at->format('d/m/Y') }}</div>
                </div>
            </div>

            {{-- Body --}}
            <div class="label-body">

                {{-- Left: Barcode + Recipient --}}
                <div class="col-left">
                    @if($order->tracking_number)
                        <div class="barcode-wrap">
                            <svg id="barcode"></svg>
                            <div class="tracking-number">{{ $order->tracking_number }}</div>
                        </div>
                    @else
                        <div class="no-tracking">ยังไม่มีหมายเลขพัสดุ</div>
                    @endif

                    <div class="recipient-wrap">
                        <div class="section-label">ผู้รับ / Receiver</div>
                        <div class="recipient-name">{{ $recipientName }}</div>
                        <div class="recipient-phone">{{ $recipientPhone }}</div>
                        <div class="recipient-addr">
                            {{ $recipientAddress }}<br>
                            {{ $recipientDistrict }} {{ $recipientProvince }}
                        </div>
                        @if($recipientPostalCode)
                            <div class="postal-code">{{ $recipientPostalCode }}</div>
                        @endif
                    </div>
                </div>

                {{-- Right: Products --}}
                <div class="col-right">
                    <div class="products-header">
                        <span class="products-title">รายการสินค้า</span>
                        <span class="items-count">{{ $order->items->sum('quantity') }} ชิ้น</span>
                    </div>
                    <div class="products-list">
                        @forelse($order->items as $item)
                            <div class="product-row">
                                <div class="product-row-top">
                                    <span class="product-title">{{ Str::limit($item->product->name, 30) }}</span>
                                    <span class="product-qty-badge">×{{ $item->quantity }}</span>
                                </div>
                                @if($item->variant_id && $item->variant)
                                    <div class="product-variant">
                                        @if($item->variant->size)
                                            <span>ไซต์: {{ $item->variant->size }}</span>
                                        @endif
                                        @if($item->variant->color)
                                            <span>สี: {{ $item->variant->color }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div style="font-size:9px;color:#9ca3af;padding:8px 0;">ไม่มีข้อมูลสินค้า</div>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="label-footer">
                <span class="footer-brand">{{ $senderName }}</span>
            </div>

        </div>
    </div>

    @if($order->tracking_number)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                JsBarcode("#barcode", "{{ $order->tracking_number }}", {
                    format: "CODE128",
                    width: 1.4,
                    height: 36,
                    displayValue: false,
                    margin: 2,
                });
            } catch(e) {
                console.warn('Barcode generation failed:', e);
            }
        });
    </script>
    @endif
</body>
</html>
