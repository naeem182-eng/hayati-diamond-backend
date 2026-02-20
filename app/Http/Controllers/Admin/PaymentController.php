<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('invoice')->latest();

        // Filter by date (optional)
        if ($request->filled('date')) {
            $query->whereDate('paid_at', $request->date);
        }

        $payments = $query->paginate(20);

        // ===== Summary Today =====
        $todayPayments = Payment::with('invoice')
            ->whereDate('paid_at', now())
            ->get();

        $todayTotal = $todayPayments->sum('amount');

        $cashToday = $todayPayments
            ->filter(fn ($p) =>
                $p->invoice?->payment_type === Invoice::PAYMENT_CASH
            )
            ->sum('amount');

        $installmentToday = $todayPayments
            ->filter(fn ($p) =>
                $p->invoice?->payment_type === Invoice::PAYMENT_INSTALLMENT
            )
            ->sum('amount');

        return view('admin.payments.index', compact(
            'payments',
            'todayTotal',
            'cashToday',
            'installmentToday'
        ));
    }
}

