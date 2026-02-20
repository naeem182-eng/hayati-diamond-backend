@extends('admin.layout')

@section('content')
<h1>Update Gold Price (18K)</h1>

@if(session('success'))
    <div style="color: green;">
        {{ session('success') }}
    </div>
@endif

@if($latest)
    <p>
        <strong>Current Price:</strong>
        {{ number_format($latest->price_per_gram, 2) }} / g
        ({{ $latest->effective_date }})
    </p>
@endif

<form method="POST" action="{{ route('admin.gold-price.store') }}">
    @csrf

    <div>
        <label>Price per gram:</label>
        <input type="number" step="0.01" name="price_per_gram" required>
    </div>

    <div>
        <label>Effective Date:</label>
        <input type="date" name="effective_date" value="{{ date('Y-m-d') }}" required>
    </div>

    <button type="submit">Save</button>
</form>

@endsection
