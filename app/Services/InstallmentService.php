<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InstallmentPlan;
use App\Models\InstallmentSchedule;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exceptions\Installment\{
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

        if ($invoice->payment_type !== Invoice::PAYMENT_INSTALLMENT) {
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

        return DB::transaction(function () use ($invoice, $months) {

            $plan = InstallmentPlan::create([
                'invoice_id'   => $invoice->id,
                'total_amount' => $invoice->total_amount,
                'months'       => $months,
                'status'       => InstallmentPlan::STATUS_ACTIVE,
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
                    'status'              => InstallmentSchedule::STATUS_UNPAID,
                ]);
            }

            return $plan;
        });
    }

    /**
     * ชำระงวด
     */
    public function markScheduleAsPaid(
        InstallmentSchedule $schedule
    ): void {

        if ($schedule->status === InstallmentSchedule::STATUS_PAID) {
            throw new ScheduleAlreadyPaidException(
                'งวดนี้ถูกชำระแล้ว'
            );
        }

        DB::transaction(function () use ($schedule) {

            // 1️⃣ สร้าง payment
            \App\Models\Payment::create([
                'invoice_id'     => $schedule->plan->invoice->id,
                'amount'         => $schedule->amount,
                'payment_method' => null,
                'note'           => null,
                'paid_at'        => now(),
            ]);

            // 2️⃣ อัปเดตงวด
            $schedule->update([
                'status'  => InstallmentSchedule::STATUS_PAID,
                'paid_at' => now(),
            ]);

            $plan = $schedule->plan;

            // 3️⃣ ถ้าไม่มีงวดค้าง → ปิด plan + invoice
            $hasUnpaid = $plan->schedules()
                ->where('status', InstallmentSchedule::STATUS_UNPAID)
                ->exists();

            if (! $hasUnpaid) {

                $plan->update([
                    'status' => InstallmentPlan::STATUS_COMPLETED,
                ]);

                $plan->invoice->update([
                    'status' => Invoice::STATUS_PAID,
                ]);
            }
        });
    }
}
