<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\InstallmentController;

/*
|--------------------------------------------------------------------------
| Sale Flow
|--------------------------------------------------------------------------
| ขายสินค้า 1 ชิ้น = 1 Invoice
*/
Route::post('/sales', [SaleController::class, 'sellSingleItem']);

/*
|--------------------------------------------------------------------------
| Installment Flow
|--------------------------------------------------------------------------
| สร้างแผนผ่อน + ชำระรายงวด
*/
Route::prefix('installments')->group(function () {

    // สร้างแผนผ่อนจาก invoice
    Route::post('/', [InstallmentController::class, 'createPlan']);

    // ชำระเงินรายงวด
    Route::post('/pay', [InstallmentController::class, 'paySchedule']);
});
