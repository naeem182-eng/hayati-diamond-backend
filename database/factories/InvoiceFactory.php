<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'customer_name'   => $this->faker->name(),
            'total_amount'    => 10000,
            'payment_type'    => 'CASH',
            'status'          => 'PAID',
            'discount_type'   => null,
            'discount_amount' => 0,
            'promotion_code'  => null,
        ];
    }

    public function installment(): static
    {
        return $this->state(fn () => [
            'payment_type' => 'INSTALLMENT',
            'status'       => 'ACTIVE',
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn () => [
            'status' => 'PAID',
        ]);
    }
}
