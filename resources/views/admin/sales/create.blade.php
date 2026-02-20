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

    <table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>Select</th>
            <th>Product</th>
            <th>Cost</th>
            <th>Market</th>
            <th>Sale Price</th>
        </tr>
    </thead>
    <tbody>
    @foreach($stockItems as $item)
        <tr>
            <td>
                <input type="checkbox"
                       name="stock_item_ids[]"
                       value="{{ $item->id }}">
            </td>
            <td>{{ $item->product->name }}</td>
            <td>{{ number_format($item->total_cost, 2) }}</td>
            <td>{{ number_format($item->market_reference, 2) }}</td>
            <td>
                <input type="number"
                       step="0.01"
                       name="sale_prices[{{ $item->id }}]">
            </td>
        </tr>
    @endforeach
    </tbody>
    </table>
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
    <select name="customer_id" id="customer_select">
    <option value="">Walk-in</option>

    @foreach($customers as $customer)
        <option
            value="{{ $customer->id }}"
            data-name="{{ $customer->name }}"
        >
            {{ $customer->name }} ({{ $customer->phone }})
        </option>
    @endforeach
    </select>
    </div>

    <button type="submit">Confirm Sale</button>
</form>

<script>
    const select = document.getElementById('customer_select');
    const nameInput = document.querySelector('[name="customer_name"]');

    select.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

    if (this.value === "") {
        nameInput.value = "";
    } else {
        nameInput.value = selectedOption.dataset.name;
    }
});
</script>


@endsection
