<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('serial_no')->unique();
            $table->string('ring_size')->nullable();

            $table->decimal('gold_weight_actual', 8, 3);
            $table->decimal('gold_price_at_make', 10, 2);

            $table->text('diamond_detail')->nullable();

            $table->decimal('total_cost', 12, 2);

            $table->enum('status', ['IN_STOCK', 'SOLD'])
                  ->default('IN_STOCK');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};
