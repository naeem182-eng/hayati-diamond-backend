<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\StockItem;
use App\Services\SaleService;
use PHPUnit\Framework\Attributes\Test;

class SaleServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_sell_a_stock_item_successfully()
    {
        // Arrange
        $product = Product::factory()->create();

        $stockItem = StockItem::factory()->create([
            'product_id' => $product->id,
            'status' => 'IN_STOCK',
            'price_sell' => 50000,
        ]);

        $service = app(SaleService::class);

        // Act
        $invoice = $service->sell([
        'stock_item_ids' => $stockItem->id,
        'customer_id'   => null,
        'customer_name' => 'Walk-in Customer',
        'payment_type'  => 'CASH',
        ]);


        // Assert
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'customer_name' => 'Walk-in Customer',
        ]);

       $this->assertDatabaseHas('invoice_items', [
            'invoice_id'     => $invoice->id,
            'stock_item_id'  => $stockItem->id,
            'price_at_sale'  => 50000,
        ]);


        $this->assertDatabaseHas('stock_items', [
            'id' => $stockItem->id,
            'status' => 'SOLD',
        ]);
    }

    #[Test]
    public function it_cannot_sell_stock_that_is_already_sold()
    {
    $product = Product::factory()->create();

    $stockItem = StockItem::factory()->create([
        'product_id' => $product->id,
        'status' => 'SOLD',
        'price_sell' => 50000,
    ]);

    $service = app(SaleService::class);

    $this->expectException(\Exception::class);

    $service->sell([
        'stock_item_ids' => $stockItem->id,
        'customer_name' => 'Test',
        'payment_type' => 'CASH',
    ]);
    }

}
