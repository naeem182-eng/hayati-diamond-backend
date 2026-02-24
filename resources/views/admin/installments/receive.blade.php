@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h1 style="margin: 0;">💰 รับเงินงวด (Receive Payment)</h1>
        <a href="{{ route('admin.installments.index') }}" style="text-decoration: none; color: #666;">⬅️ กลับหน้าบิลผ่อน</a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; align-items: start;">

        {{-- ฝั่งซ้าย: ข้อมูลงวดที่กำลังจะรับเงิน --}}
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-top: 5px solid #C8A951;">
            <h3 style="margin-top: 0; color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px;">📋 ข้อมูลการผ่อน</h3>

            <div style="margin-bottom: 15px;">
                <small style="color: gray; display: block;">เลขที่ใบแจ้งหนี้ / ลูกค้า</small>
                <strong style="font-size: 18px;">#{{ $schedule->installmentPlan->invoice->id }}</strong>
                <span style="margin: 0 10px; color: #ccc;">|</span>
                <strong style="font-size: 18px; color: #C8A951;">{{ $schedule->installmentPlan->invoice->customer->name ?? '-' }}</strong>
            </div>

            <table style="width: 100%; border-collapse: collapse;">
                <tr style="border-bottom: 1px solid #f9f9f9;">
                    <td style="padding: 10px 0; color: #666;">งวดที่:</td>
                    <td style="padding: 10px 0; text-align: right; font-weight: bold;">งวดที่ {{ $schedule->month_no }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #f9f9f9;">
                    <td style="padding: 10px 0; color: #666;">วันครบกำหนด:</td>
                    <td style="padding: 10px 0; text-align: right; color: {{ \Carbon\Carbon::parse($schedule->due_date)->isPast() ? '#dc3545' : '#333' }}; font-weight: bold;">
                        {{ \Carbon\Carbon::parse($schedule->due_date)->format('d/m/Y') }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #f9f9f9;">
                    <td style="padding: 10px 0; color: #666;">สถานะปัจจุบัน:</td>
                    <td style="padding: 10px 0; text-align: right;">
                        <span style="padding: 3px 10px; border-radius: 10px; background: #fff3cd; color: #856404; font-size: 12px; font-weight: bold;">
                            {{ $schedule->status }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 15px 0; color: #333; font-weight: bold; font-size: 18px;">ยอดที่ต้องชำระ:</td>
                    <td style="padding: 15px 0; text-align: right; color: #28a745; font-weight: bold; font-size: 22px;">
                        {{ number_format($schedule->amount, 2) }} <small style="font-size: 14px;">บาท</small>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ฝั่งขวา: ฟอร์มยืนยันการรับเงิน --}}
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0; color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px;">✍️ บันทึกการรับเงิน</h3>

            <form method="POST" action="{{ route('admin.installments.receive.store', $schedule) }}">
                @csrf

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">จำนวนเงินที่รับจริง *</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount', $schedule->amount) }}"
                           style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 18px; font-weight: bold; color: #28a745;" required>
                    @error('amount')
                        <div style="color: #dc3545; font-size: 13px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">วันที่รับเงิน</label>
                    <input type="date" name="paid_at" value="{{ old('paid_at', now()->toDateString()) }}"
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">บันทึกเพิ่มเติม (Note)</label>
                    <textarea name="note" rows="3" placeholder="เช่น โอนเข้ากสิกร / จ่ายเงินสด..."
                              style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-family: sans-serif;">{{ old('note') }}</textarea>
                </div>

                <button type="submit" style="width: 100%; background: #28a745; color: white; border: none; padding: 15px; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer;"
                        onclick="return confirm('ยืนยันการรับเงินจำนวน {{ number_format($schedule->amount, 2) }} บาท ใช่หรือไม่?')">
                    ✅ ยืนยันการรับเงิน
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
