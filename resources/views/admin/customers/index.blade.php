@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">👤 Customers Management</h2>
        <a href="{{ route('admin.customers.create') }}" style="background: #C8A951; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-weight: bold;">
            ➕ Add New Customer
        </a>
    </div>

    <div style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f4f4f4; border-bottom: 2px solid #C8A951;">
                    <th style="padding: 15px;">Name</th>
                    <th style="padding: 15px;">Phone</th>
                    <th style="padding: 15px;">Email</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px;"><strong>{{ $customer->name }}</strong></td>
                    <td style="padding: 15px;">{{ $customer->phone }}</td>
                    <td style="padding: 15px;">{{ $customer->email }}</td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="{{ route('admin.customers.show', $customer) }}" style="text-decoration: none; color: #007bff; font-weight: bold; border: 1px solid #007bff; padding: 5px 10px; border-radius: 4px; font-size: 13px;">
                            🔍 View Profile
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $customers->links('pagination::simple-bootstrap-4') }}
    </div>
</div>
@endsection
