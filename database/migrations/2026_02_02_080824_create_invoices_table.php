<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->decimal('total_amount', 12, 2);

            $table->enum('payment_type', ['CASH', 'INSTALLMENT']);
            $table->enum('status', ['ACTIVE', 'PAID']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
