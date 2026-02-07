<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstallmentSchedule;
use Illuminate\Support\Facades\DB;

class InstallmentPaymentController extends Controller
{
    /**
     * แสดงฟอร์มรับเงินงวด
     */
    public function create(InstallmentSchedule $schedule)
    {
        $schedule->load([
            'installmentPlan.invoice.customer',
        ]);

        return view('installments.receive', [
            'schedule' => $schedule,
        ]);
    }

    /**
     * บันทึกรับเงินงวด
     */
    public function store(Request $request, InstallmentSchedule $schedule)
    {
        $validated = $request->validate([
            'paid_amount' => ['required', 'numeric', 'min:0.01'],
            'note'        => ['nullable', 'string'],
            'paid_at'     => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($schedule, $validated) {

            $schedule->update([
                'paid_amount' => $validated['paid_amount'],
                'paid_at'     => $validated['paid_at'] ?? now(),
                'note'        => $validated['note'] ?? null,
                'status'      => 'PAID',
            ]);

            // เช็คว่างวดทั้งหมดจ่ายครบหรือยัง
            $plan = $schedule->installmentPlan;

            $hasUnpaid = $plan->schedules()
                ->where('status', 'UNPAID')
                ->exists();

            if (! $hasUnpaid) {
                $plan->update([
                    'status' => 'COMPLETED',
                ]);
            }
        });

        return redirect()
            ->route('invoices.show', $schedule->installmentPlan->invoice_id)
            ->with('success', 'บันทึกรับเงินงวดเรียบร้อย');
    }
}

