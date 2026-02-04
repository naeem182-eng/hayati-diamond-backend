<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StockItem;

class StockItemFactory extends Factory
{
    protected $model = StockItem::class;

    public function definition(): array
    {
    return [
        'serial_no' => fake()->uuid(),
        'status' => 'RESERVED', // default กลาง ๆ
        'gold_weight_actual' => 1.50,
        'gold_price_at_make' => 30000,
        'total_cost' => 30000,
        'price_sell' => 50000,
    ];
    }

    public function inStock(): static
    {
    return $this->state(fn () => [
        'status' => 'IN_STOCK',
    ]);
    }

}
