<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstallmentSchedule;
use App\Services\InstallmentService;
use Illuminate\Http\RedirectResponse;

class AdminInstallmentController extends Controller
{
    public function pay(
        InstallmentSchedule $schedule,
        InstallmentService $service
    ): RedirectResponse {
        $service->markScheduleAsPaid($schedule);

        return back()->with('success', 'รับชำระงวดเรียบร้อยแล้ว');
    }
}

