<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InstallmentPlan;
use App\Models\InstallmentSchedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exceptions\{
    InvoiceNotInstallmentException,
    InstallmentAlreadyExistsException,
    InvalidInstallmentMonthsException,
    ScheduleAlreadyPaidException
};

class InstallmentService
{
    /**
     * สร้างแผนผ่อนจาก Invoice
     */
    public function createPlanFromInvoice(
        Invoice $invoice,
        int $months
    ): InstallmentPlan {

        // -------- Business Rules --------

        if ($invoice->payment_type !== 'INSTALLMENT') {
            throw new InvoiceNotInstallmentException(
                'Invoice นี้ไม่ได้เลือกการชำระแบบผ่อน'
            );
        }

        if ($invoice->installmentPlan) {
            throw new InstallmentAlreadyExistsException(
                'Invoice นี้มีแผนผ่อนอยู่แล้ว'
            );
        }

        if ($months <= 0) {
            throw new InvalidInstallmentMonthsException(
                'จำนวนเดือนต้องมากกว่า 0'
            );
        }

        // -------- Transaction --------

        return DB::transaction(function () use ($invoice, $months) {

            $plan = InstallmentPlan::create([
                'invoice_id'   => $invoice->id,
                'total_amount' => $invoice->total_amount,
                'months'       => $months,
                'status'       => 'ACTIVE',
            ]);

            $monthlyAmount = round(
                $invoice->total_amount / $months,
                2
            );

            for ($month = 1; $month <= $months; $month++) {
                InstallmentSchedule::create([
                    'installment_plan_id' => $plan->id,
                    'month_no'            => $month,
                    'due_date'            => Carbon::now()->addMonths($month),
                    'amount'              => $monthlyAmount,
                    'status'              => 'UNPAID',
                ]);
            }

            return $plan;
        });
    }

    /**
     * ชำระเงินงวดหนึ่ง
     */
    public function markScheduleAsPaid(
        InstallmentSchedule $schedule
    ): void {

        if ($schedule->status === 'PAID') {
            throw new ScheduleAlreadyPaidException(
                'งวดนี้ถูกชำระแล้ว'
            );
        }

        DB::transaction(function () use ($schedule) {

            $schedule->update([
                'status'  => 'PAID',
                'paid_at' => now(),
            ]);

            $plan = $schedule->plan;

            $hasUnpaid = $plan->schedules()
                ->where('status', 'UNPAID')
                ->exists();

            if (! $hasUnpaid) {
                $plan->update([
                    'status' => 'COMPLETED',
                ]);

                $plan->invoice->update([
                    'status' => 'PAID',
                ]);
            }
        });
    }
}
