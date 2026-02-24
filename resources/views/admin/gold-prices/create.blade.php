@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h1 style="margin: 0;">🪙 Update Gold Price (18K)</h1>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; align-items: start;">

        {{-- ฝั่งซ้าย: แสดงราคาปัจจุบัน --}}
        <div style="background: linear-gradient(135deg, #C8A951 0%, #E2C986 100%); padding: 30px; border-radius: 12px; color: white; box-shadow: 0 4px 15px rgba(200, 169, 81, 0.3);">
            <h3 style="margin: 0; font-size: 18px; opacity: 0.9;">Current Market Price</h3>
            @if($latest)
                <div style="margin-top: 15px;">
                    <span style="font-size: 40px; font-weight: bold;">{{ number_format($latest->price_per_gram, 2) }}</span>
                    <span style="font-size: 18px;"> / gram</span>
                </div>
                <p style="margin-top: 10px; font-size: 14px; opacity: 0.8;">
                    📅 Effective Date: {{ \Carbon\Carbon::parse($latest->effective_date)->format('d M Y') }}
                </p>
            @else
                <p style="margin-top: 15px;">No price data available.</p>
            @endif
        </div>

        {{-- ฝั่งขวา: ฟอร์มกรอกราคาใหม่ --}}
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border: 1px solid #eee;">
            <h3 style="margin: 0 0 20px 0; color: #333;">➕ Update New Price</h3>

            <form method="POST" action="{{ route('admin.gold-price.store') }}">
                @csrf

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px;">Price per gram (THB):</label>
                    <input type="number" step="0.01" name="price_per_gram" placeholder="0.00"
                           style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px;" required>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px;">Effective Date:</label>
                    <input type="date" name="effective_date" value="{{ date('Y-m-d') }}"
                           style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px;" required>
                </div>

                <button type="submit" style="width: 100%; background: #333; color: white; border: none; padding: 15px; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s;">
                    💾 Save Price Update
                </button>
            </form>
        </div>

    </div>

    {{-- หมายเหตุเล็กๆ --}}
    <div style="margin-top: 30px; padding: 15px; background: #f9f9f9; border-radius: 8px; border-left: 4px solid #C8A951;">
        <p style="margin: 0; font-size: 13px; color: #666;">
            <strong>Note:</strong> ราคานี้จะถูกนำไปใช้คำนวณ <em>"Gold Today"</em> ในหน้าคลังสินค้า (Stock) เพื่อเปรียบเทียบกับราคาต้นทุน ณ วันที่ผลิต (Gold@Make)
        </p>
    </div>
</div>
@endsection
