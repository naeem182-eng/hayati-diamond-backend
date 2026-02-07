@extends('admin.layout')

@section('content')
<h1>บิลผ่อนค้าง</h1>

<table>
    <thead>
        <tr>
            <th>Invoice</th>
            <th>งวดที่</th>
            <th>ยอด</th>
            <th>กำหนดชำระ</th>
            <th>สถานะ</th>
            <th>จัดการ</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($installments as $item)
           <tr>
                <td>#{{ $item->installmentPlan->invoice_id }}</td>
                <td>งวดที่ {{ $item->month_no }}</td>
                <td>{{ number_format($item->amount, 2) }}</td>
                <td>{{ $item->due_date }}</td>
                <td>{{ $item->status }}</td>
                <td>
                    <a href="{{ route('installments.receive', $item) }}">
                    รับเงิน
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection



