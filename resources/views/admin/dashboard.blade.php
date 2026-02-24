@extends('admin.layout')

@section('content')
<div style="padding: 20px;">
    <h1 style="margin-bottom: 25px; color: #333;">📊 Admin Executive Dashboard</h1>

    {{-- ===== KPI CARDS SECTION ===== --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; margin-bottom: 40px;">

        <div style="background: linear-gradient(135deg, #C8A951 0%, #E2C986 100%); padding: 20px; border-radius: 15px; color: white; box-shadow: 0 4px 15px rgba(200, 169, 81, 0.3);">
            <small style="opacity: 0.9;">💰 Sales Today (ยอดวันนี้)</small>
            <div style="font-size: 24px; font-weight: bold; margin-top: 5px;">{{ number_format($salesToday, 2) }}</div>
            <div style="font-size: 12px; margin-top: 10px; background: rgba(255,255,255,0.2); display: inline-block; padding: 2px 8px; border-radius: 10px;">
                Profit: {{ number_format($profitToday, 2) }}
            </div>
        </div>

        <div style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-left: 5px solid #333;">
            <small style="color: #888;">📅 Sales This Month</small>
            <div style="font-size: 24px; font-weight: bold; color: #333; margin-top: 5px;">{{ number_format($salesThisMonth, 2) }}</div>
            <div style="color: #28a745; font-size: 13px; font-weight: bold; margin-top: 5px;">
                Margin: {{ number_format($grossMargin, 2) }} %
            </div>
        </div>

        <div style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-left: 5px solid #28a745;">
            <small style="color: #888;">💵 Profit This Month</small>
            <div style="font-size: 24px; font-weight: bold; color: #28a745; margin-top: 5px;">{{ number_format($profitThisMonth, 2) }}</div>
            <small style="color: #999;">Items Sold: {{ $totalItemsSold }} units</small>
        </div>

        <div style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-left: 5px solid #dc3545;">
            <small style="color: #888;">💳 Outstanding (ค้างชำระ)</small>
            <div style="font-size: 24px; font-weight: bold; color: #dc3545; margin-top: 5px;">{{ number_format($totalOutstanding, 2) }}</div>
            <small style="color: #999;">From {{ $totalInvoices }} Invoices</small>
        </div>

    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px;">

        {{-- ===== TOP PRODUCTS ===== --}}
        <div style="background: white; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden;">
            <div style="padding: 15px; background: #333; color: white; font-weight: bold;">🔥 Top 5 Best Sellers</div>
            <table style="width: 100%; border-collapse: collapse;">
                @foreach($topProducts as $item)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 15px;">{{ $item->product->name ?? '-' }}</td>
                    <td style="padding: 12px 15px; text-align: right; font-weight: bold; color: #C8A951;">{{ $item->total }} <small>ชิ้น</small></td>
                </tr>
                @endforeach
            </table>
        </div>

        <div style="background: white; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden;">
            <div style="padding: 15px; background: #C8A951; color: white; font-weight: bold;">💎 Top 5 Profit Makers</div>
            <table style="width: 100%; border-collapse: collapse;">
                @foreach($topProfitProducts as $item)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 15px;">{{ $item['product']->name ?? '-' }}</td>
                    <td style="padding: 12px 15px; text-align: right; font-weight: bold; color: #28a745;">{{ number_format($item['profit'], 0) }}</td>
                </tr>
                @endforeach
            </table>
        </div>

    </div>

    {{-- ===== MONTHLY PERFORMANCE ===== --}}
    <div style="background: white; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 40px; overflow: hidden;">
        <div style="padding: 15px 20px; border-bottom: 1px solid #eee; font-weight: bold; display: flex; justify-content: space-between;">
            <span>📈 Monthly Performance ({{ now()->year }})</span>
            <span style="color: #C8A951;">Total Profit: {{ number_format($totalProfit, 2) }}</span>
        </div>
        <div style="padding: 0 20px 20px 20px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="color: #999; font-size: 12px;">
                        <th style="padding: 15px 10px;">เดือน</th>
                        <th style="padding: 15px 10px; text-align: right;">Sales</th>
                        <th style="padding: 15px 10px; text-align: right;">Profit</th>
                        <th style="padding: 15px 10px; text-align: center;">Growth</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(range(1,12) as $month)
                    @if($monthlySales[$month] > 0)
                    <tr style="border-bottom: 1px solid #f9f9f9;">
                        <td style="padding: 10px; font-weight: bold; color: #555;">{{ date("F", mktime(0, 0, 0, $month, 1)) }}</td>
                        <td style="padding: 10px; text-align: right;">{{ number_format($monthlySales[$month], 0) }}</td>
                        <td style="padding: 10px; text-align: right; color: #28a745; font-weight: bold;">{{ number_format($monthlyProfit[$month], 0) }}</td>
                        <td style="padding: 10px; text-align: center;"><span style="color: #C8A951;">●</span></td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== LATEST INVOICES ===== --}}
    <h3 style="margin-bottom: 15px;">🆕 Latest Invoices (รายการล่าสุด)</h3>
    <div style="background: white; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #fafafa; font-size: 13px;">
                <tr>
                    <th style="padding: 15px; text-align: left;">Invoice</th>
                    <th style="padding: 15px; text-align: left;">Customer</th>
                    <th style="padding: 15px; text-align: right;">Total</th>
                    <th style="padding: 15px; text-align: center;">Payment</th>
                    <th style="padding: 15px; text-align: center;">Status</th>
                    <th style="padding: 15px; text-align: center;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($latestInvoices as $invoice)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px;"><strong>#{{ $invoice->id }}</strong></td>
                    <td style="padding: 15px;">{{ $invoice->customer_name ?? '-' }}</td>
                    <td style="padding: 15px; text-align: right; font-weight: bold;">{{ number_format($invoice->total_amount, 2) }}</td>
                    <td style="padding: 15px; text-align: center;">
                        <small style="background: #eee; padding: 2px 8px; border-radius: 4px;">{{ $invoice->payment_type }}</small>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <span style="font-size: 11px; font-weight: bold; color: {{ $invoice->status == 'PAID' ? '#28a745' : '#856404' }};">
                            {{ $invoice->status }}
                        </span>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="{{ route('admin.invoices.show', $invoice) }}" style="text-decoration: none; color: #C8A951; font-weight: bold;">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
