<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\StockItem;
use Illuminate\Support\Facades\DB;
use Exception;

class SaleService
{
    protected InstallmentService $installmentService;

    public function __construct(InstallmentService $installmentService)
    {
        $this->installmentService = $installmentService;
    }

    /**
     * ขายสินค้า 1 ชิ้น
     */
    public function sellSingleItem(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {

            // Step 1: Load + Lock Stock
            $stockItem = $this->loadStockItemForSale($data['stock_item_id']);

            // Step 2: Validation
            $this->assertStockItemCanBeSold($stockItem);

            // Step 3: Price Calculation
            [$finalPrice, $discountAmount] = $this->calculateFinalPrice(
                $stockItem->price_sell,
                $data
            );

            // Step 4: Create Invoice (ต้องมาก่อน)
            $invoice = $this->createInvoice(
                $data,
                $finalPrice,
                $discountAmount
            );

            // Step 5: Create Installment Plan (ถ้าเป็นผ่อน)
            if ($invoice->payment_type === Invoice::PAYMENT_INSTALLMENT) {

                $months = (int) ($data['installment_months'] ?? 0);

                if ($months <= 0) {
                    throw new Exception('ต้องระบุจำนวนเดือนผ่อน');
                }

                $this->installmentService
                    ->createPlanFromInvoice($invoice, $months);
            }

            // Step 6: Create Invoice Item (snapshot)
            $this->createInvoiceItem($invoice, $stockItem, $finalPrice);

            // Step 7: Mark Stock SOLD
            $this->markStockItemAsSold($stockItem);

            return $invoice;
        });
    }

    /* ==============================
     * Helpers
     * ============================== */

    protected function loadStockItemForSale(int $id): StockItem
    {
        return StockItem::lockForUpdate()->findOrFail($id);
    }

    protected function assertStockItemCanBeSold(StockItem $stockItem): void
    {
        if ($stockItem->status !== StockItem::STATUS_IN_STOCK) {
            throw new Exception('สินค้านี้ถูกขายไปแล้ว');
        }

        if ($stockItem->price_sell === null) {
            throw new Exception('สินค้านี้ยังไม่ได้ตั้งราคาขาย');
        }
    }

    protected function calculateFinalPrice(float $basePrice, array $data): array
    {
        $discountAmount = 0;

        if (!empty($data['discount_type']) && !empty($data['discount_value'])) {
            if ($data['discount_type'] === Invoice::DISCOUNT_FIXED) {
                $discountAmount = $data['discount_value'];
            }

            if ($data['discount_type'] === Invoice::DISCOUNT_PERCENT) {
                $discountAmount = ($basePrice * $data['discount_value']) / 100;
            }
        }

        return [max($basePrice - $discountAmount, 0), $discountAmount];
    }

    protected function createInvoice(
        array $data,
        float $finalPrice,
        float $discountAmount
    ): Invoice {
        $paymentType = $data['payment_type'] ?? Invoice::PAYMENT_CASH;

        return Invoice::create([
            'customer_id'     => $data['customer_id']   ?? null,
            'customer_name'   => $data['customer_name'] ?? null,
            'total_amount'    => $finalPrice,
            'discount_amount' => $discountAmount,
            'discount_type'   => $data['discount_type'] ?? null,
            'promotion_code'  => $data['promotion_code'] ?? null,
            'payment_type'    => $paymentType,
            'status'          => $paymentType === Invoice::PAYMENT_INSTALLMENT
                ? Invoice::STATUS_ACTIVE
                : Invoice::STATUS_PAID,
        ]);
    }

    protected function createInvoiceItem(
        Invoice $invoice,
        StockItem $stockItem,
        float $price
    ): void {
        InvoiceItem::create([
            'invoice_id'    => $invoice->id,
            'stock_item_id' => $stockItem->id,
            'product_id'    => $stockItem->product_id,
            'price_at_sale' => $price,
            'quantity'      => 1,
        ]);
    }

    protected function markStockItemAsSold(StockItem $stockItem): void
    {
        $stockItem->update([
            'status' => StockItem::STATUS_SOLD,
        ]);
    }
}

