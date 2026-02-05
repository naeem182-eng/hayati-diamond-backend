<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StockItemController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\InstallmentController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('stock-items', StockItemController::class)
        ->only(['index', 'create', 'store']);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('sales', [SaleController::class, 'store'])->name('sales.store');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])
        ->name('invoices.show');

    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'printPdf'])
        ->name('invoices.print');
});

Route::post(
    'admin/invoices/{invoice}/installments',
    [InstallmentController::class, 'store']
)->name('admin.installments.store');

Route::post(
    '/admin/installments/{schedule}/pay',
    [\App\Http\Controllers\Admin\AdminInstallmentController::class, 'pay']
)->name('admin.installments.pay');

Route::post(
    'admin/installment-schedules/{schedule}/pay',
    [InstallmentController::class, 'payFromAdmin']
)->name('admin.installments.pay');


