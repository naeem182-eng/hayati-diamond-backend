@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    <h1>✏️ Edit Stock Item: {{ $stockItem->serial_no }}</h1>

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px;">
        <form action="{{ route('admin.stock-items.update', $stockItem->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 15px;">
                <label>Product Model:</label><br>
                <select name="product_id" style="width: 100%; padding: 8px; margin-top: 5px;" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $stockItem->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Serial Number:</label>
                <input type="text" name="serial_no" value="{{ old('serial_no', $stockItem->serial_no) }}" style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>

            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label>Ring Size:</label>
                    <input type="text" name="ring_size" value="{{ old('ring_size', $stockItem->ring_size) }}" style="width: 100%; padding: 8px; margin-top: 5px;">
                </div>
                <div style="flex: 1;">
                    <label>Gold Weight (g):</label>
                    <input type="number" step="0.001" name="gold_weight_actual" value="{{ old('gold_weight_actual', $stockItem->gold_weight_actual) }}" style="width: 100%; padding: 8px; margin-top: 5px;" required>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label>Gold Price @Make:</label>
                    <input type="number" step="0.01" name="gold_price_at_make" value="{{ old('gold_price_at_make', $stockItem->gold_price_at_make) }}" style="width: 100%; padding: 8px; margin-top: 5px;" required>
                </div>
                <div style="flex: 1;">
                    <label>Total Cost:</label>
                    <input type="number" step="0.01" name="total_cost" value="{{ old('total_cost', $stockItem->total_cost) }}" style="width: 100%; padding: 8px; margin-top: 5px;" required>
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Diamond Detail:</label>
                <textarea name="diamond_detail" rows="3" style="width: 100%; padding: 8px; margin-top: 5px;">{{ old('diamond_detail', $stockItem->diamond_detail) }}</textarea>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button type="submit" style="background: #C8A951; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                    💾 Update Stock Item
                </button>
                <a href="{{ route('admin.stock-items.index') }}" style="text-decoration: none; color: #666; padding: 10px; font-size: 14px;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
