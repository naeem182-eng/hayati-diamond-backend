<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstallmentSchedule;
use App\Services\InstallmentService;
use Illuminate\Http\RedirectResponse;

class AdminInstallmentController extends Controller
{
    public function index()
    {
    $installments = \App\Models\InstallmentSchedule::with('invoice')
        ->where('status', 'UNPAID')
        ->orderBy('due_date')
        ->get();

    return view('admin.installments.index', compact('installments'));
    }

    public function pay(
    InstallmentSchedule $schedule,
    InstallmentService $service
    ): RedirectResponse
    {

    $schedule->update
    ([
        'payment_method' => request('payment_method'),
        'note' => request('note'),
    ]);

    $service->markScheduleAsPaid($schedule);

    return back()->with('success', 'รับชำระงวดเรียบร้อยแล้ว');
    }
}

