@extends('admin.layout')

@section('content')

<h2>➕ Add Customer</h2>

<form method="POST" action="{{ route('admin.customers.store') }}">
    @csrf

    <div>
        Name:
        <input type="text" name="name" required>
    </div>

    <div>
        Phone:
        <input type="text" name="phone">
    </div>

    <div>
        Email:
        <input type="email" name="email">
    </div>

    <div>
        Address:
        <textarea name="address"></textarea>
    </div>

    <button type="submit">Save</button>
</form>

@endsection
