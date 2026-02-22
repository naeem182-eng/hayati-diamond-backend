<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StockItemController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\AdminInstallmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\GoldPriceController;

use App\Http\Controllers\InstallmentController;

// use App\Http\Controllers\InstallmentPaymentController;
// use Illuminate\Support\Facades\Auth;

// use App\Models\Invoice;

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    //Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);

    // Customers
    Route::resource('customers', CustomerController::class);

    // Gold Price
    Route::get('gold-price', [GoldPriceController::class, 'create'])->name('gold-price.create');
    Route::post('gold-price', [GoldPriceController::class, 'store'])->name('gold-price.store');

    // Stock Items
    Route::resource('stock-items', StockItemController::class)
        ->only(['index', 'create', 'store']);

    // Sales
    Route::get('sales/create', [SaleController::class, 'create'])
        ->name('sales.create');
    Route::post('sales', [SaleController::class, 'store'])
        ->name('sales.store');

    //Payment Index
    Route::resource('payments', PaymentController::class)
    ->only(['index']);

    // Invoices
    Route::get('invoices', [InvoiceController::class, 'index'])
    ->name('invoices.index');

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

require __DIR__.'/auth.php';




