<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StockItemController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\AdminInstallmentController;

use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\InstallmentPaymentController;

use App\Models\Invoice;

/*
|--------------------------------------------------------------------------
| Root
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $latestInvoices = Invoice::latest()
        ->take(10)
        ->get();

        return view('admin.dashboard', compact('latestInvoices'));
    })->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);

    // Stock Items
    Route::resource('stock-items', StockItemController::class)
        ->only(['index', 'create', 'store']);

    // Sales
    Route::get('sales/create', [SaleController::class, 'create'])
        ->name('sales.create');
    Route::post('sales', [SaleController::class, 'store'])
        ->name('sales.store');

    // Invoices
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])
        ->name('invoices.show');

    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'printPdf'])
        ->name('invoices.print');

    // ===============================
    // Installments (ADMIN)
    // ===============================

    // ✅ บิลผ่อนค้าง (LIST)
    Route::get(
        'installments',
        [AdminInstallmentController::class, 'index']
    )->name('installments.index');

    // ✅ รับเงินงวด
    Route::post(
        'installments/{schedule}/pay',
        [AdminInstallmentController::class, 'pay']
    )->name('installments.pay');

    /*
|--------------------------------------------------------------------------
| Installment Receive Page (Form แยก)
|--------------------------------------------------------------------------
*/

    Route::get(
    'installments/{schedule}/receive',
    [AdminInstallmentController::class, 'receive']
    )->name('installments.receive');

    Route::post(
    'installments/{schedule}/receive',
    [AdminInstallmentController::class, 'storeReceive']
    )->name('installments.receive.store');

});

/*
|--------------------------------------------------------------------------
| Installment API / Public (ถ้ามีใช้)
|--------------------------------------------------------------------------
*/
Route::post(
    '/installments',
    [InstallmentController::class, 'createPlan']
);

Route::post(
    '/installments/pay',
    [InstallmentController::class, 'paySchedule']
);



