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

    public function sell(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {

            $stockItemIds = (array) $data['stock_item_ids'];
            $salePrices   = $data['sale_prices'] ?? [];

            $stockItems = StockItem::lockForUpdate()
                ->whereIn('id', $stockItemIds)
                ->get();

            if ($stockItems->count() !== count($stockItemIds)) {
                throw new Exception('มีสินค้าบางชิ้นไม่ถูกต้อง');
            }

            $totalBasePrice = 0;

            foreach ($stockItems as $stockItem) {

                $this->assertStockItemCanBeSold($stockItem);

                $price = $salePrices[$stockItem->id] ?? null;

                if (!$price || $price <= 0) {
                    throw new Exception("กรุณาระบุราคาขายของสินค้า #{$stockItem->id}");
                }

                $totalBasePrice += $price;
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

            if ($invoice->payment_type === Invoice::PAYMENT_CASH) {
                $invoice->payments()->create([
                    'amount' => $invoice->total_amount,
                    'paid_at' => now(),
                ]);
            }

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
                    'price_at_sale' => $salePrices[$stockItem->id],
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

    protected function createInvoice(array $data, float $finalTotal, float $discountAmount): Invoice
    {
    // 1. ดึง ID ลูกค้ามาตั้งต้น
    $customerId = $data['customer_id'] ?? null;
    $customerName = $data['customer_name'] ?? null;

    // 2. ถ้าไม่มี ID แต่มีชื่อ ลองค้นหาในระบบ (ดักเผื่อหน้าบ้านส่งมาแต่ชื่อ)
    if (!$customerId && !empty($customerName)) {
        $foundCustomer = \App\Models\Customer::where('name', $customerName)->first();
        if ($foundCustomer) {
            $customerId = $foundCustomer->id;
        }
    }

    // 3. กำหนดประเภทการชำระ (สำคัญ! ของเดิมขาดบรรทัดนี้ในฟังก์ชัน)
    $paymentType = $data['payment_type'] ?? Invoice::PAYMENT_CASH;

    // 4. บันทึกลงฐานข้อมูล
    return Invoice::create([
        'customer_id'     => $customerId,
        'customer_name'   => $customerName,
        'total_amount'    => $finalTotal,
        'discount_amount' => $discountAmount,
        'discount_type'   => $data['discount_type'] ?? null,
        'promotion_code'  => $data['promotion_code'] ?? null,
        'payment_type'    => $paymentType,
        'status'          => ($paymentType === Invoice::PAYMENT_INSTALLMENT)
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

