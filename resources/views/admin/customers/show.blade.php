@extends('admin.layout')

@section('content')

<h2>👤 Customer Detail</h2>

<p><strong>Name:</strong> {{ $customer->name }}</p>
<p><strong>Phone:</strong> {{ $customer->phone }}</p>
<p><strong>Email:</strong> {{ $customer->email }}</p>
<p><strong>Address:</strong> {{ $customer->address }}</p>

<hr>

<h3>💰 Total Spent</h3>
<p>{{ number_format($totalSpent,2) }}</p>

<h3>📉 Outstanding</h3>
<p>{{ number_format($totalOutstanding,2) }}</p>

<hr>

<h3>🧾 Invoices</h3>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>#</th>
        <th>Date</th>
        <th>Total</th>
        <th>Status</th>
        <th></th>
    </tr>

@foreach($customer->invoices as $invoice)
<tr>
    <td>#{{ $invoice->id }}</td>
    <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
    <td>{{ number_format($invoice->total_amount,2) }}</td>
    <td>{{ $invoice->status }}</td>
    <td>
        <a href="{{ route('admin.invoices.show', $invoice) }}">
            View
        </a>
    </td>
</tr>
@endforeach

</table>

@endsection
