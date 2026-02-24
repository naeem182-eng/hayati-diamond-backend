@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h1 style="margin: 0; color: #333;">👤 Customer Directory</h1>
        <a href="{{ route('admin.customers.create') }}" style="background: linear-gradient(135deg, #C8A951 0%, #B09440 100%); color: white; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; box-shadow: 0 4px 15px rgba(200, 169, 81, 0.3); display: flex; align-items: center; gap: 8px;">
            <span>➕</span> Add New Customer
        </a>
    </div>

    {{-- TABLE WRAPPER --}}
    <div style="background: white; border-radius: 15px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #333; color: #B09440;">
                    <th style="padding: 18px 20px;">Customer Name</th>
                    <th style="padding: 18px 20px;">Contact Info</th>
                    <th style="padding: 18px 20px;">Address</th>
                    <th style="padding: 18px 20px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.3s;" onmouseover="this.style.backgroundColor='#fffcf5'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 18px 20px;">
                        <div style="font-weight: bold; color: #333; font-size: 16px;">{{ $customer->name }}</div>
                        <small style="color: #999;">ID: #CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</small>
                    </td>
                    <td style="padding: 18px 20px;">
                        <div style="margin-bottom: 4px;">📞 <span style="color: #555;">{{ $customer->phone ?? '-' }}</span></div>
                        <div>📧 <span style="color: #888; font-size: 13px;">{{ $customer->email ?? '-' }}</span></div>
                    </td>
                    <td style="padding: 18px 20px; max-width: 300px;">
                        <div style="color: #666; font-size: 14px; line-height: 1.4;">
                            {{ $customer->address ?? 'No address provided' }}
                        </div>
                    </td>
                    <td style="padding: 18px 20px; text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 10px;">
                            <a href="{{ route('admin.customers.show', $customer) }}" style="text-decoration: none; color: #C8A951; font-weight: bold; border: 1.5px solid #C8A951; padding: 7px 15px; border-radius: 6px; font-size: 13px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#C8A951'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#C8A951';">
                                🔍 View Profile
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach

                @if($customers->isEmpty())
                <tr>
                    <td colspan="4" style="padding: 50px; text-align: center; color: #999;">
                        <div style="font-size: 40px; margin-bottom: 10px;">📋</div>
                        No customers found. Click "Add New Customer" to start.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div style="margin-top: 25px;">
        {{ $customers->links('pagination::simple-bootstrap-4') }}
    </div>
</div>
@endsection
