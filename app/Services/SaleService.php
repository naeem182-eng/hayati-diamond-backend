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
     * ขายสินค้าหลายชิ้นในใบเดียว
     */
    public function sell(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {

            $stockItems = StockItem::lockForUpdate()
                ->whereIn('id', $data['stock_item_ids'])
                ->get();

            if ($stockItems->count() !== count($data['stock_item_ids'])) {
                throw new Exception('มีสินค้าบางชิ้นไม่ถูกต้อง');
            }

            $totalBasePrice = 0;

            foreach ($stockItems as $stockItem) {

                $this->assertStockItemCanBeSold($stockItem);

                $totalBasePrice += $stockItem->price_sell;
            }

            [$finalTotal, $discountAmount] = $this->calculateFinalPrice(
                $totalBasePrice,
                $data
            );

            $invoice = $this->createInvoice(
                $data,
                $finalTotal,
                $discountAmount
            );

            if ($invoice->payment_type === Invoice::PAYMENT_INSTALLMENT) {

                $months = (int) ($data['installment_months'] ?? 0);

                if ($months <= 0) {
                    throw new Exception('ต้องระบุจำนวนเดือนผ่อน');
                }

                $this->installmentService
                    ->createPlanFromInvoice($invoice, $months);
            }

            foreach ($stockItems as $stockItem) {

                InvoiceItem::create([
                    'invoice_id'    => $invoice->id,
                    'stock_item_id' => $stockItem->id,
                    'product_id'    => $stockItem->product_id,
                    'price_at_sale' => $stockItem->price_sell,
                    'quantity'      => 1,
                ]);

                $this->markStockItemAsSold($stockItem);
            }

            return $invoice;
        });
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

        $final = max($basePrice - $discountAmount, 0);

        return [$final, $discountAmount];
    }

    protected function createInvoice(
        array $data,
        float $finalTotal,
        float $discountAmount
    ): Invoice {

        $paymentType = $data['payment_type'] ?? Invoice::PAYMENT_CASH;

        return Invoice::create([
            'customer_id'     => $data['customer_id']   ?? null,
            'customer_name'   => $data['customer_name'] ?? null,
            'total_amount'    => $finalTotal,
            'discount_amount' => $discountAmount,
            'discount_type'   => $data['discount_type'] ?? null,
            'promotion_code'  => $data['promotion_code'] ?? null,
            'payment_type'    => $paymentType,
            'status'          => $paymentType === Invoice::PAYMENT_INSTALLMENT
                ? Invoice::STATUS_ACTIVE
                : Invoice::STATUS_PAID,
        ]);
    }

    protected function markStockItemAsSold(StockItem $stockItem): void
    {
        $stockItem->update([
            'status' => StockItem::STATUS_SOLD,
        ]);
    }
}
