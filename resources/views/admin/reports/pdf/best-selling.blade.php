@extends('admin.reports.pdf.layout')

@section('title', 'รายงานสินค้าขายดี')
@section('subtitle', 'ช่วงวันที่ ' . $startDate->format('d/m/Y') . ' — ' . $endDate->format('d/m/Y'))

@section('content')
    <table>
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>ชื่อสินค้า</th>
                <th class="text-center">จำนวนขาย</th>
                <th class="text-right">ยอดขาย (บาท)</th>
                <th class="text-center">จำนวนออเดอร์</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $idx => $p)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $p->product_name }}</td>
                    <td class="text-center">{{ number_format($p->total_sold) }}</td>
                    <td class="text-right">฿{{ number_format($p->total_revenue, 0) }}</td>
                    <td class="text-center">{{ number_format($p->order_count) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
