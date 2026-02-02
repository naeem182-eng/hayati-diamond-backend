<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('installment_plans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')
                  ->constrained()
                  ->cascadeOnDelete()
                  ->unique(); // 1 invoice ต่อ 1 แผนผ่อน

            $table->decimal('total_amount', 12, 2);
            $table->integer('months');

            $table->enum('status', ['ACTIVE', 'COMPLETED'])
                  ->default('ACTIVE');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installment_plans');
    }
};
