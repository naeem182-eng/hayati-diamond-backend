<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        h2, h3 {
            margin: 0;
        }

        .header {
            margin-bottom: 20px;
        }

        .right {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        img {
            max-width: 90px;
        }

        .section {
            margin-top: 25px;
        }

        .badge-paid {
            color: green;
            font-weight: bold;
        }

        .badge-unpaid {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

{{-- ================= HEADER ================= --}}
<div class="header">
    <h2>Hayati Diamond</h2>
    <p>
        Invoice #{{ $invoice->id }} <br>
        Date: {{ $invoice->created_at->format('Y-m-d') }}
    </p>
</div>

<p>
    <strong>Customer:</strong>
    {{ $invoice->customer_name ?? '-' }}
</p>

{{-- ================= ITEMS ================= --}}
<div class="section">
    <h3>Items</h3>

    <table>
        <thead>
            <tr>
                <th width="20%">Image</th>
                <th>Product</th>
                <th width="20%" class="right">Price</th>
            </tr>
        </thead>
        <tbody>
        @foreach($invoice->items as $item)
            <tr>
                <td>
                    @if($item->product_image_snapshot)
                        <img src="{{ $item->product_image_snapshot }}">
                    @else
                        -
                    @endif
                </td>
                <td>
                    {{ $item->product_name_snapshot ?? 'Ring' }}
                </td>
                <td class="right">
                    {{ number_format($item->price_at_sale, 2) }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p class="right">
        <strong>Total: {{ number_format($invoice->total_amount, 2) }}</strong>
    </p>
</div>

{{-- ================= INSTALLMENT ================= --}}
@if($invoice->payment_type === \App\Models\Invoice::PAYMENT_INSTALLMENT && $invoice->installmentPlan)

<div class="section">
    <h3>Installment Plan</h3>

    <p>
        <strong>Months:</strong> {{ $invoice->installmentPlan->months }} <br>
        <strong>Status:</strong> {{ $invoice->installmentPlan->status }}
    </p>

    <table>
        <thead>
            <tr>
                <th width="10%">#</th>
                <th>Due Date</th>
                <th class="right">Amount</th>
                <th width="20%">Status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($invoice->installmentPlan->schedules as $schedule)
            <tr>
                <td>{{ $schedule->month_no }}</td>
                <td>{{ \Carbon\Carbon::parse($schedule->due_date)->format('Y-m-d') }}</td>
                <td class="right">
                    {{ number_format($schedule->amount, 2) }}
                </td>
                <td>
                    @if($schedule->status === 'PAID')
                        <span class="badge-paid">
                            PAID
                            @if($schedule->paid_at)
                                ({{ \Carbon\Carbon::parse($schedule->paid_at)->format('Y-m-d') }})
                            @endif
                        </span>
                    @else
                        <span class="badge-unpaid">UNPAID</span>
                    @endif
                </td>
            </tr>
        @endforeach
        @php
        $totalPaid = $invoice->installmentPlan
        ->schedules
        ->where('status', 'PAID')
        ->sum('amount');

        $remaining = max($invoice->total_amount - $totalPaid, 0);
        @endphp

        <p class="right" style="margin-top:10px;">
            <strong>Paid:</strong> {{ number_format($totalPaid, 2) }} <br>
            <strong>Remaining:</strong> {{ number_format($remaining, 2) }}
        </p>

        </tbody>
    </table>
    <div class="section" style="margin-top:40px;">
    <table style="border:none;">
        <tr>
            <td style="border:none; width:50%; text-align:center;">
                <p>__________________________</p>
                <p><strong>Seller Signature</strong></p>
            </td>
            <td style="border:none; width:50%; text-align:center;">
                <p>__________________________</p>
                <p><strong>Customer Signature</strong></p>
            </td>
        </tr>
    </table>

    <p style="margin-top:20px; font-size:11px;">
        <strong>Note:</strong><br>
        สินค้าจะถูกส่งมอบหลังจากชำระครบทุกงวดตามข้อตกลง
        หากผิดนัดชำระ ทางร้านขอสงวนสิทธิ์ในการระงับการส่งมอบสินค้า
    </p>
</div>

</div>

@endif

</body>
</html>
