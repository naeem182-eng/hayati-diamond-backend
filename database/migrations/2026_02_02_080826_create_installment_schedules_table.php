<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('installment_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('installment_plan_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->integer('month_no');
            $table->date('due_date');

            $table->decimal('amount', 12, 2);

            $table->enum('status', ['UNPAID', 'PAID'])
                  ->default('UNPAID');

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installment_schedules');
    }
};
