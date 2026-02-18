@extends('admin.layout')

@section('content')
<h1>Admin Dashboard</h1>

{{-- KPI CARDS --}}
<div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:30px;">

    <div style="padding:15px; border:1px solid #ddd; width:200px;">
        <strong>💰 Sales Today</strong><br>
        {{ number_format($salesToday,2) }}
    </div>

    <div style="padding:15px; border:1px solid #ddd; width:200px;">
        <strong>💵 Profit Today</strong><br>
        {{ number_format($profitToday,2) }}
    </div>

    <div style="padding:15px; border:1px solid #ddd; width:200px;">
        <strong>📅 Sales This Month</strong><br>
        {{ number_format($salesThisMonth,2) }}
    </div>

    <div style="padding:15px; border:1px solid #ddd; width:200px;">
        <strong>💵 Profit This Month</strong><br>
        {{ number_format($profitThisMonth,2) }}
    </div>

    <div style="padding:15px; border:1px solid #ddd; width:200px;">
        <strong>🧾 Total Invoices</strong><br>
        {{ $totalInvoices }}
    </div>

    <div style="padding:15px; border:1px solid #ddd; width:200px;">
        <strong>💎 Total Profit</strong><br>
        {{ number_format($totalProfit,2) }}
    </div>

    <div style="padding:15px; border:1px solid #ddd; width:200px;">
        <strong>📊 Gross Margin (This Month)</strong><br>
        {{ number_format($grossMargin,2) }} %
    </div>

    <div style="padding:15px; border:1px solid #ddd; width:200px;">
        <strong>📦 Items Sold</strong><br>
        {{ $totalItemsSold }}
    </div>

    <div style="padding:15px; border:1px solid #ddd; width:200px;">
        <strong>💳 Outstanding Installments</strong><br>
        {{ number_format($totalOutstanding,2) }}
    </div>

</div>

{{-- TOP PRODUCTS --}}
<h3>🔥 Top 5 Products</h3>

<table>
    <tr>
        <th>Product</th>
        <th>Sold</th>
    </tr>
@foreach($topProducts as $item)
    <tr>
        <td>{{ $item->product->name ?? '-' }}</td>
        <td>{{ $item->total }}</td>
    </tr>
@endforeach
</table>

<h3>🔥 Top 5 Profit Products</h3>

<table>
    <tr>
        <th>Product</th>
        <th>Profit</th>
    </tr>

@foreach($topProfitProducts as $item)
<tr>
    <td>{{ $item['product']->name ?? '-' }}</td>
    <td>{{ number_format($item['profit'],2) }}</td>
</tr>
@endforeach
</table>

<h3>📈 Monthly Profit ({{ now()->year }})</h3>

<table>
    <tr>
        <th>Month</th>
        <th>Sales</th>
        <th>Profit</th>
    </tr>

@foreach(range(1,12) as $month)
<tr>
    <td>{{ $month }}</td>
    <td>{{ number_format($monthlySales[$month],2) }}</td>
    <td>{{ number_format($monthlyProfit[$month],2) }}</td>
</tr>
@endforeach
</table>

<br>

{{-- LATEST INVOICES --}}
<h3>🆕 Latest Invoices</h3>

<table>
    <tr>
        <th>#</th>
        <th>Customer</th>
        <th>Total</th>
        <th>Payment</th>
        <th>Status</th>
        <th></th>
    </tr>

@foreach($latestInvoices as $invoice)
<tr>
    <td>#{{ $invoice->id }}</td>
    <td>{{ $invoice->customer_name ?? '-' }}</td>
    <td>{{ number_format($invoice->total_amount,2) }}</td>
    <td>{{ $invoice->payment_type }}</td>
    <td>{{ $invoice->status }}</td>
    <td>
        <a href="{{ route('admin.invoices.show', $invoice) }}">
            ดู
        </a>
    </td>
</tr>
@endforeach
</table>

@endsection

