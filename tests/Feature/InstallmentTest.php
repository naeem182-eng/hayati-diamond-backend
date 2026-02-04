<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\Invoice;
use App\Models\InstallmentPlan;
use App\Models\InstallmentSchedule;
use PHPUnit\Framework\Attributes\Test;

class InstallmentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_installment_plan_successfully()
    {
        // Arrange: สร้าง invoice ที่ขายแล้ว
        $product = Product::factory()->create();

        $stockItem = StockItem::factory()->create([
            'product_id' => $product->id,
            'price_sell' => 60000,
            'status'     => 'IN_STOCK',
        ]);

        $invoice = Invoice::create([
            'customer_name' => 'Installment Customer',
            'total_amount'  => 60000,
            'payment_type'  => 'INSTALLMENT',
            'status'        => 'ACTIVE',
        ]);

        // Act
        $response = $this->postJson('/api/installments', [
            'invoice_id' => $invoice->id,
            'months'     => 6,
        ]);

        // Assert
        $response->assertStatus(201);

        $this->assertDatabaseHas('installment_plans', [
            'invoice_id'   => $invoice->id,
            'months'       => 6,
            'total_amount' => 60000,
            'status'       => 'ACTIVE',
        ]);

        $this->assertEquals(
            6,
            InstallmentSchedule::count()
        );
    }

    #[Test]
    public function it_marks_invoice_and_plan_completed_when_last_installment_is_paid()
    {
    // Arrange
    $invoice = Invoice::factory()->create([
        'payment_type' => 'INSTALLMENT',
        'status'       => 'ACTIVE',
        'total_amount' => 30000,
    ]);

    $plan = InstallmentPlan::create([
        'invoice_id'   => $invoice->id,
        'total_amount' => 30000,
        'months'       => 3,
        'status'       => 'ACTIVE',
    ]);

    $schedules = collect([1, 2, 3])->map(function ($month) use ($plan) {
        return InstallmentSchedule::create([
            'installment_plan_id' => $plan->id,
            'month_no' => $month,
            'due_date' => now()->addMonths($month),
            'amount'   => 10000,
        ]);
    });

    // Act: จ่ายทุกงวด
    foreach ($schedules as $schedule) {
        $this->postJson('/api/installments/pay', [
            'schedule_id' => $schedule->id,
        ])->assertOk();
    }

    // Assert
    $this->assertDatabaseHas('installment_plans', [
        'id'     => $plan->id,
        'status' => 'COMPLETED',
    ]);

    $this->assertDatabaseHas('invoices', [
        'id'     => $invoice->id,
        'status' => 'PAID',
    ]);
    }


    #[Test]
    public function it_cannot_create_duplicate_installment_plan_for_same_invoice()
    {
    $invoice = Invoice::factory()
        ->installment()
        ->create(['total_amount' => 50000]);

    // สร้างแผนผ่อนครั้งแรก
    InstallmentPlan::factory()->create([
        'invoice_id' => $invoice->id,
    ]);

    // ยิง API สร้างซ้ำ
    $response = $this->postJson('/api/installments', [
        'invoice_id' => $invoice->id,
        'months'     => 5,
    ]);

    $response->assertStatus(422);
    }

}
