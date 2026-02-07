@extends('admin.layout')

@section('content')

<h1>Invoice #{{ $invoice->id }}</h1>
<a
    href="{{ route('admin.invoices.print', $invoice) }}"
    target="_blank"
    style="
        display:inline-block;
        margin-bottom:15px;
        padding:6px 12px;
        background:#000;
        color:#fff;
        text-decoration:none;
    "
>
    🖨️ Print PDF
</a>

<p>
    Customer: {{ $invoice->customer_name }} <br>
    Total: {{ number_format($invoice->total_amount, 2) }} <br>
    Payment Type: {{ $invoice->payment_type }}
</p>

@if($invoice->payment_type === 'INSTALLMENT' && $invoice->installmentPlan)

    <h3>Installment Schedule</h3>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Due Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($invoice->installmentPlan->schedules as $schedule)
        <tr>
            <td>{{ $schedule->month_no }}</td>
            <td>{{ $schedule->due_date }}</td>
            <td>{{ number_format($schedule->amount, 2) }}</td>
            <td>{{ $schedule->status }}</td>
           <td>
            @if($schedule->status === 'UNPAID')
                <form
                action="{{ route('admin.installments.pay', $schedule) }}"
                method="POST"
                style="display:inline"
                >
            @csrf
                <button
                type="submit"
                class="btn btn-sm btn-primary"
                onclick="return confirm('ยืนยันรับชำระงวดนี้?')"
                >
                รับเงินงวด
                </button>
                </form>
            @else
            <span class="text-success">ชำระแล้ว</span>
            @endif
            </td>

        </tr>
    @endforeach
    </tbody>
</table>

@else
    <p>No installment plan.</p>
@endif

@endsection
