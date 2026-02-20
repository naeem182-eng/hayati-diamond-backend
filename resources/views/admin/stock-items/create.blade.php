@extends('admin.layout')

@section('content')
<h1>Create Stock Item</h1>

<form method="POST" action="{{ route('admin.stock-items.store') }}">
    @csrf

    <label>Product *</label><br>
    <select name="product_id" required>
        <option value="">-- select product --</option>
        @foreach($products as $product)
            <option value="{{ $product->id }}">{{ $product->name }}</option>
        @endforeach
    </select>
    <br><br>

    <label>Serial No</label><br>
    <input type="text" name="serial_no"><br><br>

    <label>Ring Size</label><br>
    <input type="text" name="ring_size"><br><br>

    <label>Gold Weight (Actual)</label><br>
    <input type="number" step="0.001" name="gold_weight_actual"><br><br>

    <label>Gold Price at Make</label><br>
    <input type="number" step="0.01" name="gold_price_at_make"><br><br>

    <label>Diamond Detail</label><br>
    <textarea name="diamond_detail"></textarea><br><br>

    <label>Total Cost</label><br>
    <input type="number" step="0.01" name="total_cost"><br><br>

    <button type="submit">Save</button>
</form>
@endsection
