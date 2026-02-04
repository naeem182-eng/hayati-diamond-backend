<?php

namespace Database\Factories;

use App\Models\InstallmentPlan;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InstallmentPlan>
 */
class InstallmentPlanFactory extends Factory
{
    protected $model = InstallmentPlan::class;

    public function definition(): array
    {
        return [
            'invoice_id'    => Invoice::factory(),
            'total_amount' => $this->faker->numberBetween(1_000, 50_000),
            'months'        => $this->faker->numberBetween(1, 12),
            'status'        => 'ACTIVE',
        ];
    }

    // optional แต่หล่อ
    public function completed(): self
    {
        return $this->state(fn () => [
            'status' => 'COMPLETED',
        ]);
    }
}
