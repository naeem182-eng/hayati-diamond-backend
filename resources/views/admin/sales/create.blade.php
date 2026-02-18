@extends('admin.layout')

@section('content')
<h1>New Sale</h1>

@if ($errors->any())
    <p style="color:red">{{ $errors->first() }}</p>
@endif

<form method="POST" action="{{ route('admin.sales.store') }}">
    @csrf

    {{-- Stock Item --}}
    <label>Stock Item *</label><br>
    <select name="stock_item_ids[]" multiple size="8" required>
        @foreach($stockItems as $item)
            <option
                value="{{ $item->id }}"
                {{ in_array($item->id, old('stock_item_ids', [])) ? 'selected' : '' }}
            >
                #{{ $item->id }} |
                {{ $item->product->name }} |
                {{ number_format($item->price_sell, 2) }}
            </option>
        @endforeach
    </select>
    <br><br>

    {{-- Customer --}}
    <fieldset>
        <legend>Customer (optional)</legend>

        <label>Name</label><br>
        <input type="text" name="customer_name" value="{{ old('customer_name') }}">
        <br><br>
    </fieldset>

    {{-- Payment --}}
    <label>Payment Type *</label><br>
    <select name="payment_type" id="payment_type" required>
        <option value="CASH" {{ old('payment_type') == 'CASH' ? 'selected' : '' }}>
            Cash
        </option>
        <option value="INSTALLMENT" {{ old('payment_type') == 'INSTALLMENT' ? 'selected' : '' }}>
            Installment
        </option>
    </select>
    <br><br>

    <label>Installment Months</label><br>
    <input
        type="number"
        name="installment_months"
        min="1"
        value="{{ old('installment_months') }}"
    >
    <br><br>

    {{-- Discount --}}
    <fieldset>
        <legend>Discount (optional)</legend>

        <label>Type</label><br>
        <select name="discount_type">
            <option value="">-- none --</option>
            <option value="FIXED" {{ old('discount_type') == 'FIXED' ? 'selected' : '' }}>
                Fixed
            </option>
            <option value="PERCENT" {{ old('discount_type') == 'PERCENT' ? 'selected' : '' }}>
                Percent (%)
            </option>
        </select>
        <br><br>

        <label>Value</label><br>
        <input
            type="number"
            step="0.01"
            name="discount_value"
            value="{{ old('discount_value') }}"
        >
        <br><br>

        <label>Promotion Code</label><br>
        <input
            type="text"
            name="promotion_code"
            value="{{ old('promotion_code') }}"
        >
    </fieldset>

    <br>

    <div>
    Customer:
    <select name="customer_id">
        <option value="">Walk-in</option>

        @foreach($customers as $customer)
            <option value="{{ $customer->id }}">
                {{ $customer->name }} ({{ $customer->phone }})
            </option>
        @endforeach
    </select>
    </div>

    <button type="submit">Confirm Sale</button>
</form>

@endsection
