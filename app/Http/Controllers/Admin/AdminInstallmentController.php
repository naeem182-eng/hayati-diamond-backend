<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstallmentSchedule;
use App\Services\InstallmentService;
use Illuminate\Http\Request;

class AdminInstallmentController extends Controller
{
    protected InstallmentService $installmentService;

    public function __construct(InstallmentService $installmentService)
    {
        $this->installmentService = $installmentService;
    }

    /**
     * แสดงรายการงวดที่ยังไม่ชำระ
     */
    public function index()
    {
        $installments = InstallmentSchedule::with(['plan.invoice'])
            ->where('status', InstallmentSchedule::STATUS_UNPAID)
            ->orderBy('due_date')
            ->get();

        return view('admin.installments.index', compact('installments'));
    }

    /**
     * หน้า form รับเงินงวด
     */
    public function receive(InstallmentSchedule $schedule)
    {
        return view('admin.installments.receive', compact('schedule'));
    }

    /**
     * บันทึกการรับเงินงวด
     */
    public function storeReceive(
        Request $request,
        InstallmentSchedule $schedule
    ) {
        $request->validate([
            // ตอนนี้ service ใช้ amount จาก schedule อยู่แล้ว
            // ถ้าจะรับ partial payment ค่อยเพิ่มทีหลัง
        ]);

        $this->installmentService->markScheduleAsPaid($schedule);

        return redirect()
            ->route('admin.installments.index')
            ->with('success', 'รับเงินงวดเรียบร้อยแล้ว');
    }
}
