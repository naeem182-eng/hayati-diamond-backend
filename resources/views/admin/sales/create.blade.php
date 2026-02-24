@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    <h2 style="margin-bottom: 20px;">🛒 New Sale (เปิดบิลขายใหม่)</h2>

    @if ($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            ⚠️ {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.sales.store') }}">
        @csrf

        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 25px; align-items: start;">

            {{-- ฝั่งซ้าย: รายการ Stock สินค้า --}}
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
                <div style="padding: 15px; background: #f8f4e8; border-bottom: 2px solid #C8A951;">
                    <strong style="color: #333;">📦 เลือกสินค้าจากคลัง</strong>
                </div>
                <div style="max-height: 600px; overflow-y: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background: #fafafa; font-size: 13px; color: #666;">
                                <th style="padding: 12px; text-align: center;">เลือก</th>
                                <th style="padding: 12px;">สินค้า</th>
                                <th style="padding: 12px; text-align: right;">ต้นทุน (Cost)</th>
                                <th style="padding: 12px; text-align: right;">ราคากลาง</th>
                                <th style="padding: 12px; text-align: right; width: 150px;">ราคาขายจริง</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockItems as $item)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 12px; text-align: center;">
                                    <input type="checkbox" name="stock_item_ids[]" value="{{ $item->id }}" style="transform: scale(1.2); accent-color: #C8A951;">
                                </td>
                                <td style="padding: 12px;">
                                    <strong style="display: block;">{{ $item->product->name }}</strong>
                                    <small style="color: #888;">#{{ $item->id }}</small>
                                </td>
                                <td style="padding: 12px; text-align: right; color: #999;">{{ number_format($item->total_cost, 0) }}</td>
                                <td style="padding: 12px; text-align: right; font-weight: bold; color: #C8A951;">{{ number_format($item->market_reference, 0) }}</td>
                                <td style="padding: 12px; text-align: right;">
                                    <input type="number" step="0.01" name="sale_prices[{{ $item->id }}]"
                                           placeholder="ใส่ราคาขาย"
                                           style="width: 100px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; text-align: right;">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ฝั่งขวา: รายละเอียดลูกค้าและการชำระเงิน --}}
            <div style="display: flex; flex-direction: column; gap: 20px;">

                {{-- Customer Box --}}
                <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <h3 style="margin: 0 0 15px 0; font-size: 16px; color: #333;">👤 ข้อมูลลูกค้า</h3>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 13px; margin-bottom: 5px;">เลือกลูกค้าที่มีอยู่</label>
                        <select name="customer_id" id="customer_select" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ddd;">
                            <option value="">-- Walk-in (ลูกค้าทั่วไป) --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" data-name="{{ $customer->name }}">
                                    {{ $customer->name }} ({{ $customer->phone }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; margin-bottom: 5px;">ชื่อลูกค้าที่แสดงในบิล</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                               style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ddd; background: #f9f9f9;">
                    </div>
                </div>

                {{-- Payment Box --}}
                <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <h3 style="margin: 0 0 15px 0; font-size: 16px; color: #333;">💰 การชำระเงิน</h3>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 13px; margin-bottom: 5px;">ประเภทการชำระ *</label>
                        <select name="payment_type" id="payment_type" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ddd; font-weight: bold; color: #28a745;">
                            <option value="CASH" {{ old('payment_type') == 'CASH' ? 'selected' : '' }}>เงินสด (Cash)</option>
                            <option value="INSTALLMENT" {{ old('payment_type') == 'INSTALLMENT' ? 'selected' : '' }}>ผ่อนชำระ (Installment)</option>
                        </select>
                    </div>

                    <div id="installment_section" style="margin-bottom: 15px; display: none; padding: 10px; background: #fff9e6; border-radius: 8px;">
                        <label style="display: block; font-size: 13px; margin-bottom: 5px;">จำนวนงวด (เดือน)</label>
                        <input type="number" name="installment_months" min="1" value="{{ old('installment_months', 6) }}"
                               style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #f0ad4e;">
                    </div>

                    <div style="padding-top: 10px; border-top: 1px solid #eee;">
                        <label style="display: block; font-size: 13px; margin-bottom: 5px;">ส่วนลด (Discount)</label>
                        <div style="display: flex; gap: 5px;">
                            <select name="discount_type" style="padding: 10px; border-radius: 6px; border: 1px solid #ddd;">
                                <option value="">ไม่มี</option>
                                <option value="FIXED" {{ old('discount_type') == 'FIXED' ? 'selected' : '' }}>฿</option>
                                <option value="PERCENT" {{ old('discount_type') == 'PERCENT' ? 'selected' : '' }}>%</option>
                            </select>
                            <input type="number" step="0.01" name="discount_value" value="{{ old('discount_value') }}" placeholder="0.00"
                                   style="flex: 1; padding: 10px; border-radius: 6px; border: 1px solid #ddd;">
                        </div>
                    </div>
                </div>

                <button type="submit" style="width: 100%; padding: 18px; background: #333; color: white; border: none; border-radius: 12px; font-size: 18px; font-weight: bold; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                    Confirm & Open Bill
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // จัดการเลือกลูกค้า
    const select = document.getElementById('customer_select');
    const nameInput = document.querySelector('[name="customer_name"]');
    select.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        nameInput.value = (this.value === "") ? "" : selectedOption.dataset.name;
    });

    // จัดการแสดงผลส่วนของการผ่อน
    const paymentType = document.getElementById('payment_type');
    const installmentSection = document.getElementById('installment_section');

    function toggleInstallment() {
        installmentSection.style.display = (paymentType.value === 'INSTALLMENT') ? 'block' : 'none';
    }

    paymentType.addEventListener('change', toggleInstallment);
    window.onload = toggleInstallment; // ตรวจสอบสถานะตอนโหลดหน้าใหม่ (เผื่อ Validation error)
</script>
@endsection
