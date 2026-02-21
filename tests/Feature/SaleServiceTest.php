<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
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
        ]);

        $service = app(SaleService::class);

        // Act
        $invoice = $service->sell([
            'stock_item_ids' => [$stockItem->id],
            'sale_prices'    => [$stockItem->id => 50000],
            'customer_id'    => null,
            'customer_name'  => 'Walk-in Customer',
            'payment_type'   => 'CASH',
        ]);

        // Assert
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'customer_name' => 'Walk-in Customer',
        ]);

        $this->assertDatabaseHas('invoice_items', [
            'invoice_id'    => $invoice->id,
            'stock_item_id' => $stockItem->id,
            'price_at_sale' => 50000,
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
        ]);

        $service = app(SaleService::class);

        $this->expectException(\Exception::class);

        $service->sell([
            'stock_item_ids' => [$stockItem->id],
            'sale_prices'    => [$stockItem->id => 50000],
            'customer_name'  => 'Test',
            'payment_type'   => 'CASH',
        ]);
    }

    #[Test]
    public function test_it_requires_sale_prices()
    {
        $this->actingAs(User::factory()->create());

        $item = StockItem::factory()->inStock()->create();

        $response = $this->post(route('admin.sales.store'), [
            'stock_item_ids' => [$item->id],
            'payment_type'   => 'CASH',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('sale_prices');
    }

    #[Test]
    public function test_sale_price_must_be_positive()
    {
        $this->actingAs(User::factory()->create());

        $item = StockItem::factory()->inStock()->create();

        $response = $this->post(route('admin.sales.store'), [
            'stock_item_ids' => [$item->id],
            'sale_prices'    => [
                $item->id => 0
            ],
            'payment_type'   => 'CASH',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('sale_prices.' . $item->id);
    }
}
