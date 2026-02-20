@extends('admin.layout')

@section('content')
<h1>Stock Items</h1>

<a href="{{ route('admin.stock-items.create') }}">➕ New Stock Item</a>

<table border="1" cellpadding="6" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Product Pic</th>
            <th>Serial</th>
            <th>Ring Size</th>
            <th>Gold (g)</th>
            <th>Total Cost</th>
            <th>Gold @ Make</th>
            <th>Gold Today</th>
            <th>Δ Gold</th>
            <th>Sold Price</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach($stockItems as $item)
        <tr>
            <td>{{ $item->id }}</td>

            <td>{{ $item->product->name }}</td>

            <td>
                @if($item->product && $item->product->image_url)
                    <img src="{{ asset('images/products/' . $item->product->image_url) }}" width="60">
                @else
                    -
                @endif
            </td>

            <td>{{ $item->serial_no ?? '-' }}</td>

            <td>{{ $item->ring_size ?? '-' }}</td>

            <td>
                {{ $item->gold_weight_actual
                    ? number_format($item->gold_weight_actual, 2)
                    : '-' }}
            </td>

            <td>
                {{ $item->total_cost
                    ? number_format($item->total_cost, 2)
                    : '-' }}
            </td>

            <td>
                {{ $item->gold_price_at_make
                    ? number_format($item->gold_price_at_make, 2)
                    : '-' }}
            </td>

            <td>
                {{ $item->current_gold_price
                    ? number_format($item->current_gold_price, 2)
                    : '-' }}
            </td>

            <td>
                {{ number_format($item->gold_difference, 2) }}
            </td>

            <td>
                @if($item->status === \App\Models\StockItem::STATUS_SOLD && $item->invoiceItem)
                    {{ number_format($item->invoiceItem->price_at_sale, 0) }}
                @else
                    -
                @endif
            </td>

            <td>{{ $item->status }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<br>

{{ $stockItems->links() }}

@endsection
