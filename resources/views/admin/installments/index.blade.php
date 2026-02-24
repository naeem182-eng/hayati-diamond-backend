@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h1 style="margin: 0;">📑 บิลผ่อนค้าง (Installments)</h1>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f8f4e8; border-bottom: 2px solid #C8A951;">
                    <th style="padding: 15px; color: #333;">Invoice</th>
                    <th style="padding: 15px; color: #333;">งวดที่</th>
                    <th style="padding: 15px; color: #333; text-align: right;">ยอดชำระ</th>
                    <th style="padding: 15px; color: #333; text-align: center;">กำหนดชำระ</th>
                    <th style="padding: 15px; color: #333; text-align: center;">สถานะ</th>
                    <th style="padding: 15px; color: #333; text-align: center;">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($installments as $item)
                <tr style="border-bottom: 1px solid #eee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fdfbf5'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 15px;">
                        <strong style="color: #C8A951;">#{{ $item->installmentPlan->invoice_id }}</strong>
                    </td>
                    <td style="padding: 15px;">งวดที่ {{ $item->month_no }}</td>
                    <td style="padding: 15px; text-align: right; font-weight: bold;">
                        {{ number_format($item->amount, 2) }}
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <span style="color: {{ \Carbon\Carbon::parse($item->due_date)->isPast() && $item->status !== 'PAID' ? '#dc3545' : '#333' }}; font-weight: {{ \Carbon\Carbon::parse($item->due_date)->isPast() ? 'bold' : 'normal' }}">
                            {{ \Carbon\Carbon::parse($item->due_date)->format('d/m/Y') }}
                        </span>
                        @if(\Carbon\Carbon::parse($item->due_date)->isPast() && $item->status !== 'PAID')
                            <br><small style="color: #dc3545; font-size: 10px;">(เกินกำหนด)</small>
                        @endif
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        @php
                            $statusBg = '#fff3cd'; $statusColor = '#856404';
                            if($item->status === 'PAID') { $statusBg = '#d4edda'; $statusColor = '#155724'; }
                            elseif($item->status === 'OVERDUE') { $statusBg = '#f8d7da'; $statusColor = '#721c24'; }
                        @endphp
                        <span style="padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold; background: {{ $statusBg }}; color: {{ $statusColor }};">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        @if($item->status !== 'PAID')
                            <a href="{{ route('admin.installments.receive', $item) }}"
                               style="background: #28a745; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: bold; display: inline-block;">
                               💰 รับเงิน
                            </a>
                        @else
                            <span style="color: #6c757d; font-size: 13px;">✅ ชำระแล้ว</span>
                        @endif
                    </td>
                </tr>
                @endforeach

                @if($installments->isEmpty())
                <tr>
                    <td colspan="6" style="padding: 30px; text-align: center; color: #999;">ไม่พบรายการบิลผ่อนค้าง</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
