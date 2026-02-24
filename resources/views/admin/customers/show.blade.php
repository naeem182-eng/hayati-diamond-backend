@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">👤 Customer Detail</h2>
        <a href="{{ route('admin.customers.index') }}" style="text-decoration: none; color: #666;">⬅️ Back to List</a>
    </div>

    {{-- ส่วนข้อมูลลูกค้า --}}
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <p><strong>Name:</strong> {{ $customer->name }}</p>
                <p><strong>Phone:</strong> {{ $customer->phone }}</p>
            </div>
            <div>
                <p><strong>Email:</strong> {{ $customer->email }}</p>
                <p><strong>Address:</strong> {{ $customer->address }}</p>
            </div>
        </div>
    </div>

    {{-- ส่วนสรุปยอดเงิน --}}
    <div style="display: flex; gap: 20px; margin-bottom: 25px;">
        <div style="flex: 1; background: #d4edda; padding: 20px; border-radius: 8px; border-left: 5px solid #28a745;">
            <h3 style="margin: 0; color: #155724; font-size: 16px;">💰 Total Spent</h3>
            <p style="font-size: 24px; font-weight: bold; margin: 10px 0 0 0;">{{ number_format($totalSpent, 2) }}</p>
        </div>
        <div style="flex: 1; background: #f8d7da; padding: 20px; border-radius: 8px; border-left: 5px solid #dc3545;">
            <h3 style="margin: 0; color: #721c24; font-size: 16px;">📉 Outstanding</h3>
            <p style="font-size: 24px; font-weight: bold; margin: 10px 0 0 0;">{{ number_format($totalOutstanding, 2) }}</p>
        </div>
    </div>

    {{-- ส่วนตาราง Invoices --}}
    <h3 style="margin-bottom: 15px;">🧾 Invoices History</h3>
    <div style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f4f4f4; border-bottom: 2px solid #C8A951;">
                    <th style="padding: 12px;"># ID</th>
                    <th style="padding: 12px;">Date</th>
                    <th style="padding: 12px; text-align: right;">Total Amount</th>
                    <th style="padding: 12px; text-align: center;">Status</th>
                    <th style="padding: 12px; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customer->invoices as $invoice)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;">#{{ $invoice->id }}</td>
                    <td style="padding: 12px;">{{ $invoice->created_at->format('Y-m-d') }}</td>
                    <td style="padding: 12px; text-align: right; font-weight: bold;">{{ number_format($invoice->total_amount, 2) }}</td>
                    <td style="padding: 12px; text-align: center;">
                        <span style="padding: 4px 8px; border-radius: 12px; font-size: 11px; background: {{ $invoice->status == 'PAID' ? '#d4edda' : '#fff3cd' }};">
                            {{ $invoice->status }}
                        </span>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="{{ route('admin.invoices.show', $invoice) }}" style="text-decoration: none; color: #007bff; font-weight: bold;">
                            🔍 View
                        </a>
                    </td>
                </tr>
                @endforeach
                @if($customer->invoices->isEmpty())
                <tr>
                    <td colspan="5" style="padding: 20px; text-align: center; color: #999;">No invoice history found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
