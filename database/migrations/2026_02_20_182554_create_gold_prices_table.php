<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gold_prices', function (Blueprint $table) {
            $table->id();

            // ราคาทอง 18K ต่อกรัม
            $table->decimal('price_per_gram', 12, 2);

            // วันที่เริ่มใช้ราคา
            $table->date('effective_date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gold_prices');
    }
};
