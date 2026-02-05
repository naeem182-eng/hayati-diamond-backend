@extends('admin.layout')

@section('content')
<h1>Stock Items</h1>

<a href="{{ route('admin.stock-items.create') }}">➕ New Stock Item</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Serial</th>
            <th>Ring Size</th>
            <th>Cost</th>
            <th>Price Sell</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach($stockItems as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->serial_no ?? '-' }}</td>
            <td>{{ $item->ring_size ?? '-' }}</td>
            <td>{{ $item->total_cost ? number_format($item->total_cost, 2) : '-' }}</td>
            <td>{{ number_format($item->price_sell, 2) }}</td>
            <td>{{ $item->status }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $stockItems->links() }}
@endsection
