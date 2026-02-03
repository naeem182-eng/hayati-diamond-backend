<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InstallmentPlan;
use App\Models\InstallmentSchedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InstallmentService
{
    /**
     * สร้างแผนผ่อนจาก Invoice
     */
    public function createPlanFromInvoice(Invoice $invoice, int $months): InstallmentPlan
    {
        // 1. ตรวจสอบเงื่อนไขพื้นฐาน (Business Rules)
        if ($invoice->payment_type !== 'INSTALLMENT') {
            throw new \Exception('Invoice นี้ไม่ได้เลือกการชำระแบบผ่อน');
        }

        if ($invoice->installmentPlan) {
            abort(422,'Invoice นี้มีแผนผ่อนอยู่แล้ว');
        }

        if ($months <= 0) {
            throw new \Exception('จำนวนเดือนต้องมากกว่า 0');
        }

        // 2. ใช้ Transaction เพื่อให้ข้อมูล atomic
        return DB::transaction(function () use ($invoice, $months) {

            // 3. สร้าง Installment Plan
            $plan = InstallmentPlan::create([
                'invoice_id'   => $invoice->id,
                'total_amount' => $invoice->total_amount,
                'months'       => $months,
                'status'       => 'ACTIVE',
            ]);

            // 4. คำนวณเงินผ่อนต่อเดือน
            $monthlyAmount = round(
                $invoice->total_amount / $months,
                2
            );

            // 5. สร้างตารางผ่อนรายเดือน (Installment Schedules)
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
    public function markScheduleAsPaid(InstallmentSchedule $schedule): void
    {
        if ($schedule->status === 'PAID') {
            throw new \Exception('งวดนี้ถูกชำระแล้ว');
        }

        DB::transaction(function () use ($schedule) {

            // 1. อัปเดตงวดเป็น PAID
            $schedule->update([
                'status'  => 'PAID',
                'paid_at' => now(),
            ]);

            $plan = $schedule->plan;

            // 2. ตรวจว่ายังมีงวดที่ยังไม่จ่ายไหม
            $hasUnpaid = $plan->schedules()
                ->where('status', 'UNPAID')
                ->exists();

            // 3. ถ้าจ่ายครบทุกงวด → ปิดแผนผ่อน + invoice
            if (!$hasUnpaid) {
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
