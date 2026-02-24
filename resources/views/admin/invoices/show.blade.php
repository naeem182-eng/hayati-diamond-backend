@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 25px;">
        <div>
            <h1 style="margin: 0; color: #333;">Invoice #{{ $invoice->id }}</h1>
            <p style="color: gray; margin-top: 5px;">ออกเมื่อ: {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.invoices.print', $invoice) }}" target="_blank"
               style="display: inline-block; padding: 10px 20px; background: #333; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                🧾 พิมพ์ใบเสร็จ (Print)
            </a>
            <a href="{{ route('admin.invoices.index') }}" style="display: inline-block; padding: 10px 20px; background: #eee; color: #333; text-decoration: none; border-radius: 6px;">
                กลับหน้ารวม
            </a>
        </div>
    </div>

    {{-- Info Cards --}}
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 30px;">
        {{-- Customer & Summary --}}
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-left: 5px solid #C8A951;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <small style="color: gray; display: block; margin-bottom: 5px;">ลูกค้า</small>
                    <strong style="font-size: 20px; color: #C8A951;">{{ $invoice->customer_name }}</strong>
                </div>
                <div>
                    <small style="color: gray; display: block; margin-bottom: 5px;">ประเภทการชำระ</small>
                    <strong style="font-size: 16px;">{{ $invoice->payment_type }}</strong>
                </div>
            </div>
        </div>

        {{-- Total Amount Highlight --}}
        <div style="background: #333; color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;">
            <small style="opacity: 0.8; display: block; margin-bottom: 5px;">ยอดเงินสุทธิ</small>
            <strong style="font-size: 28px;">{{ number_format($invoice->total_amount, 2) }}</strong>
            <small style="display: block; margin-top: 5px;">บาท</small>
        </div>
    </div>

    {{-- Installment Section --}}
    @if($invoice->payment_type === 'INSTALLMENT' && $invoice->installmentPlan)
        <h3 style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
            📅 ตารางผ่อนชำระ (Installment Schedule)
        </h3>

        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: #f8f4e8; border-bottom: 2px solid #C8A951;">
                        <th style="padding: 15px;">งวดที่</th>
                        <th style="padding: 15px;">กำหนดชำระ</th>
                        <th style="padding: 15px; text-align: right;">จำนวนเงิน</th>
                        <th style="padding: 15px; text-align: center;">สถานะ</th>
                        <th style="padding: 15px; text-align: center;">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($invoice->installmentPlan->schedules as $schedule)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px; font-weight: bold;">{{ $schedule->month_no }}</td>
                        <td style="padding: 15px; color: {{ \Carbon\Carbon::parse($schedule->due_date)->isPast() && $schedule->status !== 'PAID' ? '#dc3545' : '#333' }}">
                            {{ \Carbon\Carbon::parse($schedule->due_date)->format('d/m/Y') }}
                        </td>
                        <td style="padding: 15px; text-align: right; font-weight: bold;">
                            {{ number_format($schedule->amount, 2) }}
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            @if($schedule->status === 'PAID')
                                <span style="background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                    ชำระแล้ว
                                </span>
                            @else
                                <span style="background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                    รอชำระ
                                </span>
                            @endif
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            @if($schedule->status === 'UNPAID')
                                <a href="{{ route('admin.installments.receive', $schedule) }}"
                                   style="background: #28a745; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: bold;">
                                    💰 รับเงินงวด
                                </a>
                            @else
                                <span style="color: #28a745; font-size: 14px;">✅ เรียบร้อย</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="padding: 30px; background: #f9f9f9; border-radius: 12px; border: 1px dashed #ccc; text-align: center; color: #999;">
            ℹ️ บิลนี้เป็นการชำระเต็มจำนวน (ไม่มีรายการผ่อนชำระ)
        </div>
    @endif
</div>
@endsection
