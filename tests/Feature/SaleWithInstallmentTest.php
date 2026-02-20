<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\Invoice;
use App\Services\SaleService;
use App\Services\InstallmentService;
use PHPUnit\Framework\Attributes\Test;

class SaleWithInstallmentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_sell_item_with_discount_and_create_installment_plan()
    {
        /**
         * -------------------------
         * Arrange (เตรียมข้อมูล)
         * -------------------------
         */

        // 1. สร้าง Product
        $product = Product::create([
            'name' => 'Diamond Ring',
        ]);

        // 2. สร้าง StockItem พร้อมราคาขาย
        $stockItem = StockItem::create([
            'product_id'           => $product->id,
            'serial_no'            => 'RING-001',
            'gold_weight_actual'   => 3.5,
            'gold_price_at_make'   => 20000,
            'total_cost'           => 30000,
            'status'               => 'IN_STOCK',
        ]);

        $saleService = app(SaleService::class);
        $installmentService = app(InstallmentService::class);

        /**
         * -------------------------
         * Act (ลงมือทำจริง)
         * -------------------------
         */

        // 3. ขายสินค้า + ส่วนลด 10%
        $invoice = $saleService->sell([
        'stock_item_ids'     => [$stockItem->id],
        'sale_prices' => [$stockItem->id => 50000,],
        'customer_name'      => 'Walk-in Customer',
        'payment_type'       => Invoice::PAYMENT_INSTALLMENT,
        'discount_type'      => Invoice::DISCOUNT_PERCENT,
        'discount_value'     => 10,
        'installment_months' => 5,
        ]);


        // ราคาควรเหลือ 45,000
        $this->assertEquals(45000, $invoice->total_amount);


        /**
         * -------------------------
         * Assert (ตรวจผลลัพธ์)
         * -------------------------
         */

        // Invoice ถูกต้อง
        $this->assertDatabaseHas('invoices', [
            'id'              => $invoice->id,
            'total_amount'    => 45000,
            'discount_type'   => 'PERCENT',
            'discount_amount' => 5000,
            'payment_type'    => 'INSTALLMENT',
            'status'          => 'ACTIVE',
        ]);

        // Installment Plan ถูกสร้าง
        $this->assertDatabaseHas('installment_plans', [
            'invoice_id'   => $invoice->id,
            'total_amount' => 45000,
            'months'       => 5,
            'status'       => 'ACTIVE',
        ]);

        // ต้องมีงวดผ่อนครบ 5 งวด
        $this->assertDatabaseCount('installment_schedules', 5);

        // StockItem ต้องถูกขายแล้ว
        $this->assertDatabaseHas('stock_items', [
            'id'     => $stockItem->id,
            'status' => 'SOLD',
        ]);
    }
}
