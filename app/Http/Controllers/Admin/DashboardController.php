<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfMonth = now()->startOfMonth();
        $startOfYear  = now()->startOfYear();

        $invoices = Invoice::with('items.stockItem')->get();

        $salesToday = Invoice::whereDate('created_at', today())
            ->sum('total_amount');

        $salesThisMonth = Invoice::where('created_at', '>=', $startOfMonth)
            ->sum('total_amount');

        $totalInvoices = $invoices->count();
        $totalItemsSold = InvoiceItem::sum('quantity');

        $totalOutstanding = $invoices
            ->sum(fn($invoice) => $invoice->outstanding_balance);

        $totalProfit = 0;
        $profitToday = 0;
        $profitThisMonth = 0;

        $monthlyProfit = [];
        $monthlySales  = [];

        foreach (range(1, 12) as $month) {
            $monthlyProfit[$month] = 0;
            $monthlySales[$month]  = 0;
        }

        foreach ($invoices as $invoice) {

            $invoiceProfit = 0;

            foreach ($invoice->items as $item) {
                $cost = $item->stockItem->total_cost ?? 0;
                $invoiceProfit += ($item->price_at_sale - $cost);
            }

            $totalProfit += $invoiceProfit;

            if ($invoice->created_at->isToday()) {
                $profitToday += $invoiceProfit;
            }

            if ($invoice->created_at >= $startOfMonth) {
                $profitThisMonth += $invoiceProfit;
            }

            if ($invoice->created_at->year == now()->year) {
                $m = $invoice->created_at->month;
                $monthlyProfit[$m] += $invoiceProfit;
                $monthlySales[$m]  += $invoice->total_amount;
            }
        }

        // Gross Margin %
        $grossMargin = $salesThisMonth > 0
            ? ($profitThisMonth / $salesThisMonth) * 100
            : 0;

        // Top Profit Products
        $topProfitProducts = InvoiceItem::with('stockItem', 'product')
            ->get()
            ->groupBy('product_id')
            ->map(function ($items) {
                return [
                    'product' => $items->first()->product,
                    'profit'  => $items->sum(function ($item) {
                        $cost = $item->stockItem->total_cost ?? 0;
                        return $item->price_at_sale - $cost;
                    }),
                ];
            })
            ->sortByDesc('profit')
            ->take(5);

        $latestInvoices = Invoice::latest()
            ->take(10)
            ->get();

        $topProducts = InvoiceItem::with('product')
        ->select('product_id', DB::raw('SUM(quantity) as total'))
        ->groupBy('product_id')
        ->orderByDesc('total')
        ->take(5)
        ->get();

        return view('admin.dashboard', compact(
            'salesToday',
            'salesThisMonth',
            'profitToday',
            'profitThisMonth',
            'totalProfit',
            'grossMargin',
            'totalInvoices',
            'totalItemsSold',
            'totalOutstanding',
            'monthlyProfit',
            'monthlySales',
            'topProducts',
            'topProfitProducts',
            'latestInvoices'
        ));
    }
}
