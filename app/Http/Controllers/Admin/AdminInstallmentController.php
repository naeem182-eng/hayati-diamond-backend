<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstallmentSchedule;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminInstallmentController extends Controller
{
    public function index()
    {
        $installments = InstallmentSchedule::with('invoice')
            ->where('status', 'UNPAID')
            ->orderBy('due_date')
            ->get();

        return view('admin.installments.index', compact('installments'));
    }

    public function receive(InstallmentSchedule $schedule)
    {
        return view('admin.installments.receive', compact('schedule'));
    }

    public function storeReceive(Request $request, InstallmentSchedule $schedule)
    {
        $request->validate([
            'amount'         => ['required', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string'],
            'paid_at'        => ['required', 'date'],
            'note'           => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request, $schedule) {

            // 1️⃣ สร้าง Payment record
            Payment::create([
                'invoice_id'     => $schedule->invoice->id,
                'amount'         => $request->amount,
                'payment_method' => $request->payment_method,
                'note'           => $request->note,
                'paid_at'        => $request->paid_at,
            ]);

            // 2️⃣ Mark schedule เป็น PAID
            $schedule->update([
                'status'  => 'PAID',
                'paid_at' => $request->paid_at,
            ]);

            // 3️⃣ refresh invoice status
            $schedule->invoice->refreshPaymentStatus();
        });

        return redirect()
            ->route('admin.installments.index')
            ->with('success', 'รับเงินงวดเรียบร้อยแล้ว');
    }
}
