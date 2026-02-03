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
        'status' => 'IN_STOCK',

        // REQUIRED by schema
        'gold_weight_actual' => 1.50,      // กรัม
        'gold_price_at_make' => 30000,     // ราคาตอนทำ
        'total_cost' => 30000,

        // Phase 1 sell price
        'price_sell' => 50000,
            ];
    }
}
