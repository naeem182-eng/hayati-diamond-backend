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
     * @param array{
     *   stock_item_id: int,
     *   customer_id?: int|null,
     *   customer_name?: string|null,
     *   payment_type?: string
     * } $data
     */
    public function sellSingleItem(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {

            /** -------------------------
             * 1. ดึงสินค้า
             * -------------------------- */
            $stockItem = StockItem::lockForUpdate()->findOrFail($data['stock_item_id']);

            if ($stockItem->status !== 'IN_STOCK') {
                throw new Exception('สินค้านี้ถูกขายไปแล้ว');
            }

            if (empty($stockItem->price_sell)) {
                throw new Exception('สินค้านี้ยังไม่ได้ตั้งราคาขาย');
            }

            /** -------------------------
             * 2. สร้าง Invoice
             * -------------------------- */
            $invoice = Invoice::create([
                'customer_id'   => $data['customer_id']   ?? null,
                'customer_name' => $data['customer_name'] ?? null,
                'total_amount'  => $stockItem->price_sell,
                'payment_type'  => $data['payment_type']  ?? 'CASH',
                'status'        => 'PAID',
            ]);

            /** -------------------------
             * 3. สร้าง Invoice Item
             * -------------------------- */
            InvoiceItem::create([
                'invoice_id'    => $invoice->id,
                'stock_item_id' => $stockItem->id,
                'price'         => $stockItem->price_sell,
            ]);

            /** -------------------------
             * 4. อัปเดตสถานะสินค้า
             * -------------------------- */
            $stockItem->update([
                'status' => 'SOLD',
            ]);

            return $invoice;
        });
    }
}
