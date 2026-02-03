<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SaleService;

class SaleController extends Controller
{
    protected SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    /**
     * POST /api/sales
     * ขายสินค้า 1 ชิ้น
     */
    public function sellSingleItem(Request $request)
    {
        $validated = $request->validate([
            'stock_item_id' => ['required', 'exists:stock_items,id'],
            'customer_id'   => ['nullable', 'exists:customers,id'],
            'customer_name' => ['nullable', 'string'],
            'payment_type'  => ['nullable', 'in:CASH,INSTALLMENT'],
            'discount_type' => ['nullable', 'in:FIXED,PERCENT'],
            'discount_value'=> ['nullable', 'numeric', 'min:0'],
            'promotion_code'=> ['nullable', 'string'],
        ]);

        $invoice = $this->saleService->sellSingleItem($validated);

        return response()->json([
            'message' => 'ขายสินค้าสำเร็จ',
            'data'    => $invoice,
        ], 201);
    }
}
