@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="margin: 0;">📄 Invoices (รายการใบแจ้งหนี้)</h2>
        {{-- ถ้ามีปุ่มสร้าง Invoice ใหม่ สามารถเพิ่มตรงนี้ได้ --}}
    </div>

    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f8f4e8; border-bottom: 2px solid #C8A951;">
                    <th style="padding: 15px; color: #333;">ID</th>
                    <th style="padding: 15px; color: #333;">Customer</th>
                    <th style="padding: 15px; color: #333; text-align: center;">Type</th>
                    <th style="padding: 15px; color: #333; text-align: center;">Status</th>
                    <th style="padding: 15px; color: #333; text-align: right;">Total</th>
                    <th style="padding: 15px; color: #333; text-align: right;">Outstanding</th>
                    <th style="padding: 15px; color: #333; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr style="border-bottom: 1px solid #eee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fdfbf5'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 15px;">
                        <strong style="color: #C8A951;">#{{ $invoice->id }}</strong>
                    </td>
                    <td style="padding: 15px;">
                        {{ $invoice->customer_name ?? '-' }}
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <span style="font-size: 13px; color: #666; background: #f0f0f0; padding: 2px 8px; border-radius: 4px;">
                            {{ $invoice->payment_type }}
                        </span>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        @if($invoice->status === \App\Models\Invoice::STATUS_PAID)
                            <span style="background: #d4edda; color: #155724; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold;">
                                PAID
                            </span>
                        @else
                            <span style="background: #fff3cd; color: #856404; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold;">
                                ACTIVE
                            </span>
                        @endif
                    </td>
                    <td style="padding: 15px; text-align: right; font-weight: bold;">
                        {{ number_format($invoice->total_amount, 0) }}
                    </td>
                    <td style="padding: 15px; text-align: right;">
                        @if($invoice->outstanding_balance > 0)
                            <span style="color: #dc3545; font-weight: bold;">
                                {{ number_format($invoice->outstanding_balance, 0) }}
                            </span>
                        @else
                            <span style="color: #ccc;">-</span>
                        @endif
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="{{ route('admin.invoices.show', $invoice->id) }}"
                           style="text-decoration: none; color: #007bff; font-weight: bold; font-size: 13px; border: 1px solid #007bff; padding: 5px 10px; border-radius: 4px;">
                            🔍 View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 40px; text-align: center; color: #999;">
                        🚫 ไม่พบข้อมูลใบแจ้งหนี้
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $invoices->links() }}
    </div>
</div>
@endsection
