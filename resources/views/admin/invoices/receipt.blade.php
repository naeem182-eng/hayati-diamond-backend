<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>ใบเสร็จรับเงิน #{{ $invoice->id }}</title>

<style>

body {
    font-family: thsarabun;
    font-size: 16px;
    color: #1a1a1a;
    position: relative;
}

.wrapper {
    width: 180mm;
    margin: 0 auto;
    position: relative;
    z-index: 1; /* ให้เนื้อหาอยู่เหนือ watermark */
}


/* ===== HEADER ===== */
.header-table {
    width: 100%;
}

.brand-name {
    font-size: 28px;
    font-weight: bold;
    color: #1f3c88;
    letter-spacing: 1px;
}

.document-title {
    font-size: 22px;
    font-weight: bold;
    margin-top: 5px;
}

.logo {
    width: 110px;
}

/* ===== TABLE ===== */
.table,
.installment-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.table th, .table td,
.installment-table th, .installment-table td {
    border: 1px solid #C8A951;
    padding: 6px;
}

.table th,
.installment-table th {
    background-color: #f8f4e8;
    font-weight: bold;
}

.text-right { text-align: right; }
.text-center { text-align: center; }

.summary {
    width: 100%;
    margin-top: 12px;
}

.summary td {
    padding: 5px;
}

.signature-section {
    margin-top: 40px;
}

.signature-box {
    width: 45%;
    display: inline-block;
    text-align: center;
}

.signature-line {
    margin-top: 55px;
    border-top: 1px solid #C8A951;
}

.footer {
    margin-top: 35px;
    font-size: 14px;
}

</style>
</head>

<body>
<div class="wrapper">

{{-- HEADER --}}
<table class="header-table">
<tr>
<td width="70%">
    <div class="brand-name">HAYATI DIAMOND</div>
    <div class="document-title">ใบเสร็จรับเงิน</div>

    เลขที่เอกสาร: INV-{{ $invoice->id }}<br>
    วันที่: {{ $invoice->created_at->format('d/m/Y') }}<br>
    ลูกค้า: {{ $invoice->customer_name ?? '-' }}
</td>

<td width="30%" align="right">
@if(file_exists(public_path('images/logo.png')))
    <img src="file://{{ public_path('images/logo.png') }}" class="logo">
@endif
</td>
</tr>
</table>

{{-- ITEMS --}}
<table class="table">
<tr>
    <th width="5%">#</th>
    <th width="45%">รายละเอียดสินค้า</th>
    <th width="15%">ราคาต่อหน่วย</th>
    <th width="10%">จำนวน</th>
    <th width="15%">ยอดรวม</th>
</tr>

@foreach($invoice->items as $index => $item)
<tr>
<td class="text-center">{{ $index + 1 }}</td>
<td>
    @php
        $imagePath = public_path('images/products/' . $item->product->image_url);
    @endphp

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            {{-- รูปสินค้า --}}
            <td width="70" valign="top">
                @if(!empty($item->product->image_url) && file_exists($imagePath))
                    <img src="file://{{ $imagePath }}" width="60" style="border:1px solid #eee; padding:3px;">
                @endif
            </td>

            {{-- รายละเอียด --}}
            <td valign="top" style="padding-left:8px;">

                <div style="font-weight:bold; font-size:15px;">
                    {{ $item->product->name ?? '-' }}
                </div>

                @if(!empty($item->stockItem->gold_weight_actual))
                    <div style="font-size:13px;">
                        Gold Weight: {{ $item->stockItem->gold_weight_actual }} g
                    </div>
                @endif

                @if(!empty($item->stockItem->diamond_detail))
                    <div style="font-size:13px;">
                        Diamond: {{ $item->stockItem->diamond_detail }}
                    </div>
                @endif

            </td>
        </tr>
    </table>
</td>

<td class="text-right">{{ number_format($item->price_at_sale,2) }}</td>
<td class="text-center">{{ $item->quantity }}</td>
<td class="text-right">
    {{ number_format($item->price_at_sale * $item->quantity,2) }}
</td>
</tr>
@endforeach
</table>

{{-- SUMMARY --}}
<table class="summary">
<tr>
<td width="80%" align="right">จำนวนเงินรวมทั้งสิ้น</td>
<td width="20%" align="right">{{ number_format($invoice->total_amount,2) }}</td>
</tr>
<tr>
<td align="right">ชำระแล้ว</td>
<td align="right">{{ number_format($invoice->paid_total,2) }}</td>
</tr>
<tr>
<td align="right"><strong>ยอดคงเหลือ</strong></td>
<td align="right"><strong>{{ number_format($invoice->outstanding_balance,2) }}</strong></td>
</tr>
</table>

{{-- INSTALLMENT --}}
@if($invoice->payment_type === 'INSTALLMENT' && $invoice->installmentPlan)
<h3 style="margin-top:18px;">ตารางผ่อนชำระ</h3>

<table class="installment-table">
<tr>
    <th>งวดที่</th>
    <th>กำหนดชำระ</th>
    <th>จำนวนเงิน</th>
    <th>สถานะ</th>
    <th>วันที่รับเงิน</th>
</tr>

@foreach($invoice->installmentPlan->schedules as $s)
<tr>
    <td class="text-center">{{ $s->month_no }}</td>
    <td class="text-center">{{ \Carbon\Carbon::parse($s->due_date)->format('d/m/Y') }}</td>
    <td class="text-right">{{ number_format($s->amount,2) }}</td>
    <td class="text-center">
        @if($s->status === 'PAID')
            <span style="color:green;">ชำระแล้ว</span>
        @else
            <span style="color:#C8A951;">ค้างชำระ</span>
        @endif
    </td>
    <td class="text-center">
        {{ $s->paid_at ? \Carbon\Carbon::parse($s->paid_at)->format('d/m/Y') : '-' }}
    </td>
</tr>
@endforeach

</table>
@endif

{{-- SIGN --}}
<div class="signature-section">
<table width="100%" style="margin-top:70px;">
    <tr>
        {{-- ผู้จ่ายเงิน --}}
        <td width="50%" align="center" valign="bottom">
            <div style="height:90px;"></div>
            <div style="border-top:1px solid #C8A951; width:70%; margin:0 auto;"></div>
            ___________________________<br>
            <div style="margin-top:5px;">ผู้จ่ายเงิน</div>
        </td>

        {{-- ผู้รับเงิน --}}
        <td width="50%" align="center" valign="bottom">

            @php
                $signaturePath = public_path('images/signature.png');
            @endphp

            <div style="height:90px;">
                @if(file_exists($signaturePath))
                    <img src="file://{{ $signaturePath }}"
                         style="max-height:70px; display:block; margin:0 auto;">
                @endif
            </div>
            ___________________________<br>
            <div style="border-top:1px solid #C8A951; width:70%; margin:0 auto;"></div>
            <div style="margin-top:5px;">ผู้รับเงิน</div>

        </td>
    </tr>
</table>
</div>


<div class="footer">

<table width="100%" cellpadding="0" cellspacing="0">
<tr>

    {{-- ซ้าย: ข้อมูลร้าน --}}
    <td width="60%" valign="top">
        <strong>Hayati Diamond</strong><br>
        ที่อยู่ 11/29 หมู่ 3 ต.บ้านกลาง อ.อ่าวลึก จ.กระบี่ 81110<br>
        โทร: 093-735-0033
    </td>

    {{-- ขวา: ลายเซ็น + QR --}}
    <td width="40%" align="right" valign="top">

        {{-- QR LINE --}}
        @php
            $qrPath = public_path('images/qr-line.png');
        @endphp

        @if(file_exists($qrPath))
            <img src="file://{{ $qrPath }}" width="90"><br>
            <span style="font-size:12px;">Add LINE Official</span>
        @endif

    </td>

</tr>
</table>

</div>


</div>
</body>
</html>
