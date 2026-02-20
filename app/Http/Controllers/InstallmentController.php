<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InstallmentSchedule;
use App\Services\InstallmentService;
use Illuminate\Http\Request;
use App\Exceptions\Installment\InstallmentAlreadyExistsException;
use Illuminate\Http\RedirectResponse;
use App\Exceptions\Installment\ScheduleAlreadyPaidException;

class InstallmentController extends Controller
{
    protected InstallmentService $installmentService;

    public function __construct(InstallmentService $installmentService)
    {
        $this->installmentService = $installmentService;
    }


    /**
     * สร้างแผนผ่อนจาก Invoice
     *
     * POST /api/installments
     */
    public function createPlan(Request $request)
    {
    $validated = $request->validate([
        'invoice_id' => ['required', 'exists:invoices,id'],
        'months'     => ['required', 'integer', 'min:1'],
    ]);

    $invoice = Invoice::findOrFail($validated['invoice_id']);

    try {
        $plan = $this->installmentService
            ->createPlanFromInvoice($invoice, $validated['months']);

        return response()->json([
            'message' => 'สร้างแผนผ่อนเรียบร้อย',
            'data'    => $plan->load('schedules'),
        ], 201);

    } catch (InstallmentAlreadyExistsException $e) {
        return response()->json([
            'message' => $e->getMessage(),
        ], 422);
    }
    }

    /**
     * ชำระเงินรายงวด
     *
     * POST /api/installments/pay
     */
    public function paySchedule(Request $request)
    {
    $schedule = InstallmentSchedule::findOrFail($request->schedule_id);

    try {
        $this->installmentService->markScheduleAsPaid($schedule);

        return response()->json([
            'message' => 'Payment recorded successfully'
        ], 200);

    } catch (ScheduleAlreadyPaidException $e) {

        return response()->json([
            'message' => $e->getMessage()
        ], 400);
    }
    }

    // public function payFromAdmin(
    // InstallmentSchedule $schedule
    // ): RedirectResponse {
    // $this->installmentService->markScheduleAsPaid($schedule);

    // return back()->with('success', 'ชำระงวดเรียบร้อยแล้ว');
    // }
}
