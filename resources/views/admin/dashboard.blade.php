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

    <li>
        <a href="{{ route('admin.invoices.show', 1) }}">
            🧾 Invoice (ตัวอย่าง)
        </a>
    </li>
</ul>
@endsection

