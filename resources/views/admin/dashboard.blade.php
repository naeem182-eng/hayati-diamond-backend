@extends('admin.layout')

@section('content')
<h1>Admin Dashboard</h1>

<ul>
    <li>
        <a href="{{ route('admin.products.index') }}">
            📦 Products
        </a>
    </li>

    <li>
        <a href="{{ route('admin.stock-items.index') }}">
            💍 Stock Items
        </a>
    </li>

    <li>
        <a href="{{ route('admin.sales.create') }}">
            🛒 New Sale
        </a>
    </li>
</ul>

<h3>Invoice ล่าสุด</h3>

<table>
@foreach($latestInvoices as $invoice)
<tr>
    <td>#{{ $invoice->id }}</td>
    <td>{{ $invoice->customer_name ?? '-' }}</td>
    <td>{{ number_format($invoice->total_amount,2) }}</td>
    <td>{{ $invoice->payment_type }}</td>
    <td>
        <a href="{{ route('admin.invoices.show', $invoice) }}">
            ดู
        </a>
    </td>
</tr>
@endforeach
</table>

@endsection

