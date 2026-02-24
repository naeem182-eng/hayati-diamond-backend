@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    <h1>➕ Create New Stock Item</h1>

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px;">
        <form method="POST" action="{{ route('admin.stock-items.store') }}">
            @csrf

            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Product Model *</label><br>
                <select name="product_id" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Serial Number:</label>
                <input type="text" name="serial_no" value="{{ old('serial_no') }}" placeholder="เช่น 10101..." style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="font-weight: bold;">Ring Size:</label>
                    <input type="text" name="ring_size" value="{{ old('ring_size') }}" placeholder="48, 50..." style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label style="font-weight: bold;">Gold Weight (g):</label>
                    <input type="number" step="0.001" name="gold_weight_actual" value="{{ old('gold_weight_actual') }}" placeholder="0.000" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" required>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="font-weight: bold;">Gold Price @Make:</label>
                    <input type="number" step="0.01" name="gold_price_at_make" value="{{ old('gold_price_at_make') }}" placeholder="ราคาทองตอนสั่งผลิต" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" required>
                </div>
                <div style="flex: 1;">
                    <label style="font-weight: bold;">Total Cost:</label>
                    <input type="number" step="0.01" name="total_cost" value="{{ old('total_cost') }}" placeholder="ต้นทุนรวมทั้งหมด" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" required>
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Diamond Detail:</label>
                <textarea name="diamond_detail" rows="3" placeholder="ระบุรายละเอียดเพชร เช่น GIA..." style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">{{ old('diamond_detail') }}</textarea>
            </div>

            <div style="margin-top: 25px; display: flex; gap: 10px; align-items: center;">
                <button type="submit" style="background: #C8A951; color: white; border: none; padding: 12px 25px; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 16px;">
                    💾 Save New Stock Item
                </button>
                <a href="{{ route('admin.stock-items.index') }}" style="text-decoration: none; color: #666; font-size: 14px;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
