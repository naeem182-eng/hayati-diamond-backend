<?php

namespace Database\Factories;

use App\Models\InstallmentSchedule;
use App\Models\InstallmentPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstallmentScheduleFactory extends Factory
{
    protected $model = InstallmentSchedule::class;

    public function definition(): array
    {
        return [
            'installment_plan_id' => InstallmentPlan::factory(),
            'month_no' => 1,
            'due_date' => now()->addMonth(),
            'amount' => 1000,
            'paid_at' => null,
            'status' => InstallmentSchedule::STATUS_UNPAID,
        ];
    }
}

