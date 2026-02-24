@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="margin: 0;">💰 Payments Ledger (สมุดบัญชีรายรับ)</h2>

        {{-- ===== Filter Section ===== --}}
        <form method="GET" style="display: flex; gap: 10px; align-items: center; background: white; padding: 10px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <label style="font-size: 14px; color: #666;">เลือกวันที่:</label>
            <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}"
                   style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <button type="submit" style="background: #333; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
                🔍 Filter
            </button>
            @if(request('date'))
                <a href="{{ route('admin.payments.index') }}" style="text-decoration: none; font-size: 13px; color: #666;">ล้างค่า</a>
            @endif
        </form>
    </div>

    {{-- ===== Summary Dashboard ===== --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #C8A951 0%, #E2C986 100%); padding: 20px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(200, 169, 81, 0.2);">
            <small style="opacity: 0.9; display: block; margin-bottom: 5px;">ยอดรับรวมวันนี้</small>
            <strong style="font-size: 24px;">{{ number_format($todayTotal, 2) }}</strong>
            <span style="font-size: 14px;"> THB</span>
        </div>

        <div style="background: white; padding: 20px; border-radius: 12px; border-left: 5px solid #28a745; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <small style="color: gray; display: block; margin-bottom: 5px;">Cash Sale (ขายสด)</small>
            <strong style="font-size: 24px; color: #28a745;">{{ number_format($cashToday, 2) }}</strong>
        </div>

        <div style="background: white; padding: 20px; border-radius: 12px; border-left: 5px solid #ffc107; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <small style="color: gray; display: block; margin-bottom: 5px;">Installment Received (ค่างวด)</small>
            <strong style="font-size: 24px; color: #856404;">{{ number_format($installmentToday, 2) }}</strong>
        </div>
    </div>

    {{-- ===== Table Section ===== --}}
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f8f4e8; border-bottom: 2px solid #C8A951;">
                    <th style="padding: 15px; color: #333;">วันที่/เวลา</th>
                    <th style="padding: 15px; color: #333;">Invoice</th>
                    <th style="padding: 15px; color: #333;">Customer</th>
                    <th style="padding: 15px; color: #333; text-align: center;">Type</th>
                    <th style="padding: 15px; color: #333; text-align: right;">Amount</th>
                    <th style="padding: 15px; color: #333;">Note</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr style="border-bottom: 1px solid #eee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fdfbf5'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 15px; font-size: 14px; color: #666;">
                        {{ $payment->paid_at?->format('d/m/Y H:i') }}
                    </td>
                    <td style="padding: 15px;">
                        <a href="{{ route('admin.invoices.show', $payment->invoice_id) }}"
                           style="color: #C8A951; text-decoration: none; font-weight: bold;">
                           #{{ $payment->invoice_id }}
                        </a>
                    </td>
                    <td style="padding: 15px;">
                        {{ $payment->invoice->customer_name ?? '-' }}
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        @if($payment->invoice?->payment_type === \App\Models\Invoice::PAYMENT_CASH)
                            <span style="background: #e9f7ef; color: #1e8449; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold;">CASH</span>
                        @else
                            <span style="background: #fff9e6; color: #9a7d0a; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold;">INSTALLMENT</span>
                        @endif
                    </td>
                    <td style="padding: 15px; text-align: right; font-weight: bold; color: #333;">
                        {{ number_format($payment->amount, 2) }}
                    </td>
                    <td style="padding: 15px; font-size: 13px; color: #888; font-style: italic;">
                        {{ $payment->note ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 40px; text-align: center; color: #999;">
                        ไม่พบข้อมูลรายรับในรายการนี้
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $payments->links() }}
    </div>
</div>
@endsection
