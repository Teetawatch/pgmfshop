@extends('admin.reports.pdf.layout')

@section('title', 'รายงานสต็อกต่ำ')
@section('subtitle', 'เกณฑ์: สต็อก ≤ ' . $threshold . ' ชิ้น — ณ วันที่ ' . now()->format('d/m/Y'))

@section('content')
    <table>
        <thead>
            <tr>
                <th>รหัส</th>
                <th>ชื่อสินค้า</th>
                <th>หมวดหมู่</th>
                <th class="text-center">สต็อก</th>
                <th class="text-center">ขายแล้ว</th>
                <th class="text-right">ราคา</th>
                <th class="text-right">มูลค่าคงเหลือ</th>
                <th class="text-center">สถานะ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->category->name ?? '-' }}</td>
                    <td class="text-center"><strong>{{ $p->stock }}</strong></td>
                    <td class="text-center">{{ number_format($p->sold) }}</td>
                    <td class="text-right">฿{{ number_format($p->price, 0) }}</td>
                    <td class="text-right">฿{{ number_format($p->stock * $p->price, 0) }}</td>
                    <td class="text-center">
                        @if($p->stock === 0)
                            <span class="badge badge-danger">หมด</span>
                        @elseif($p->stock <= 5)
                            <span class="badge badge-warning">วิกฤต</span>
                        @else
                            <span class="badge badge-info">ต่ำ</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
