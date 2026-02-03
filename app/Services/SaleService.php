<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\StockItem;
use Illuminate\Support\Facades\DB;
use Exception;

class SaleService
{
    /**
     * ขายสินค้า 1 ชิ้น
     *
     * Use Case:
     * - สินค้าต้องยังไม่ถูกขาย
     * - ต้องมีราคาขาย
     * - รองรับส่วนลด / โปรโมชัน
     * - 1 Invoice ต่อ 1 StockItem
     *
     * @param array{
     *   stock_item_id: int,
     *   customer_id?: int|null,
     *   customer_name?: string|null,
     *   payment_type?: 'CASH'|'INSTALLMENT',
     *   discount_type?: 'FIXED'|'PERCENT',
     *   discount_value?: float,
     *   promotion_code?: string|null
     * }
     */
    public function sellSingleItem(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {

            // Step 1: Load + Lock Stock
            $stockItem = $this->loadStockItemForSale($data['stock_item_id']);

            // Step 2: Business Validation
            $this->assertStockItemCanBeSold($stockItem);

            // Step 3: Calculate Price (รวม Discount)
            [$finalPrice, $discountAmount] = $this->calculateFinalPrice(
                $stockItem->price_sell,
                $data
            );

            // Step 4: Create Invoice
            $invoice = $this->createInvoice(
                $stockItem,
                $data,
                $finalPrice,
                $discountAmount
            );

            // Step 5: Create Invoice Item
            $this->createInvoiceItem($invoice, $stockItem, $finalPrice);

            // Step 6: Update Stock Status
            $this->markStockItemAsSold($stockItem);

            return $invoice;
        });
    }

    /** -------------------------
     * Step 1: Load + Lock
     * -------------------------- */
    protected function loadStockItemForSale(int $stockItemId): StockItem
    {
        return StockItem::lockForUpdate()->findOrFail($stockItemId);
    }

    /** -------------------------
     * Step 2: Business Validation
     * -------------------------- */
    protected function assertStockItemCanBeSold(StockItem $stockItem): void
    {
        if ($stockItem->status !== 'IN_STOCK') {
            throw new Exception('สินค้านี้ถูกขายไปแล้ว');
        }

        if (empty($stockItem->price_sell)) {
            throw new Exception('สินค้านี้ยังไม่ได้ตั้งราคาขาย');
        }
    }

    /** -------------------------
     * Step 3: Calculate Final Price
     * -------------------------- */
    protected function calculateFinalPrice(float $basePrice, array $data): array
    {
        $discountAmount = 0;

        if (!empty($data['discount_type']) && !empty($data['discount_value'])) {

            if ($data['discount_type'] === 'FIXED') {
                $discountAmount = $data['discount_value'];
            }

            if ($data['discount_type'] === 'PERCENT') {
                $discountAmount = ($basePrice * $data['discount_value']) / 100;
            }
        }

        // ป้องกันราคาติดลบ
        $finalPrice = max($basePrice - $discountAmount, 0);

        return [$finalPrice, $discountAmount];
    }

    /** -------------------------
     * Step 4: Create Invoice
     * -------------------------- */
    protected function createInvoice(
        StockItem $stockItem,
        array $data,
        float $finalPrice,
        float $discountAmount
    ): Invoice {
        return Invoice::create([
            'customer_id'      => $data['customer_id']   ?? null,
            'customer_name'    => $data['customer_name'] ?? null,

            // ราคาหลังหักส่วนลดแล้ว
            'total_amount'     => $finalPrice,

            // ข้อมูลส่วนลด
            'discount_amount'  => $discountAmount,
            'discount_type'    => $data['discount_type'] ?? null,
            'promotion_code'   => $data['promotion_code'] ?? null,

            'payment_type'     => $data['payment_type'] ?? 'CASH',

            // ถ้าเป็นผ่อน → ACTIVE (รอ InstallmentService)
            'status'           => ($data['payment_type'] ?? 'CASH') === 'INSTALLMENT'
                                    ? 'ACTIVE'
                                    : 'PAID',
        ]);
    }

    /** -------------------------
     * Step 5: Create Invoice Item
     * -------------------------- */
    protected function createInvoiceItem(
        Invoice $invoice,
        StockItem $stockItem,
        float $finalPrice
    ): void {
        InvoiceItem::create([
            'invoice_id'    => $invoice->id,
            'stock_item_id' => $stockItem->id,
            'product_id'    => $stockItem->product_id,
            'price_at_sale' => $finalPrice,
            'quantity'      => 1,
        ]);
    }

    /** -------------------------
     * Step 6: Update Stock Status
     * -------------------------- */
    protected function markStockItemAsSold(StockItem $stockItem): void
    {
        $stockItem->update([
            'status' => 'SOLD',
        ]);
    }
}
