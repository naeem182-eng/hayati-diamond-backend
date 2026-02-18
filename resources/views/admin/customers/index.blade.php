@extends('admin.layout')

@section('content')

<h2>👤 Customers</h2>

<a href="{{ route('admin.customers.create') }}">➕ Add Customer</a>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th></th>
    </tr>

@foreach($customers as $customer)
<tr>
    <td>{{ $customer->name }}</td>
    <td>{{ $customer->phone }}</td>
    <td>{{ $customer->email }}</td>
    <td>
        <a href="{{ route('admin.customers.show', $customer) }}">
            View
        </a>
    </td>
</tr>
@endforeach

</table>

<br>
{{ $customers->links() }}

@endsection
