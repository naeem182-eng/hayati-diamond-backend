@extends('admin.layout')

@section('content')
<h1>รับเงินงวด</h1>

<p>
    Invoice #{{ $schedule->installmentPlan->invoice->id }}<br>
    ลูกค้า: {{ $schedule->installmentPlan->invoice->customer->name ?? '-' }}
</p>

<hr>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>งวดที่</th>
        <td>{{ $schedule->month_no }}</td>
    </tr>
    <tr>
        <th>วันครบกำหนด</th>
        <td>{{ $schedule->due_date }}</td>
    </tr>
    <tr>
        <th>ยอดงวด</th>
        <td>{{ number_format($schedule->amount, 2) }}</td>
    </tr>
    <tr>
        <th>สถานะ</th>
        <td>{{ $schedule->status }}</td>
    </tr>
</table>

<hr>
    <form method="POST" action="{{ route('admin.installments.receive.store', $schedule) }}">
    @csrf

    <div>
        <label>จำนวนเงินที่รับ *</label><br>
        <input
            type="number"
            name="amount"
            step="0.01"
            value="{{ old('amount', $schedule->amount) }}"
            required
        >
        @error('amount')
            <div style="color:red">{{ $message }}</div>
        @enderror
    </div>

    <br>

    <div>
        <label>วันที่รับเงิน</label><br>
        <input
            type="date"
            name="paid_at"
            value="{{ old('paid_at', now()->toDateString()) }}"
        >
    </div>

    <br>

    <div>
        <label>Note (โอน / เงินสด / รายละเอียดอื่น)</label><br>
        <textarea name="note" rows="3">{{ old('note') }}</textarea>
    </div>

    <br>

    <button type="submit">
        ยืนยันรับเงินงวด
    </button>
</form>

@endsection
