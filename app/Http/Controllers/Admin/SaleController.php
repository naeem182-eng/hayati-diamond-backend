<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleRequest;
use App\Models\StockItem;
use App\Models\Customer;
use App\Services\SaleService;
use Exception;

class SaleController extends Controller
{
    public function create()
    {
        $stockItems = StockItem::where('status', StockItem::STATUS_IN_STOCK)
        ->with('product')
        ->get();

        $customers = Customer::orderBy('name')->get();

        return view('admin.sales.create', compact('stockItems', 'customers'));
    }


    public function store(SaleRequest $request, SaleService $service)
    {
        try {

            $invoice = $service->sell($request->validated());

            return redirect()
                ->route('admin.invoices.show', $invoice)
                ->with('success', 'Sale completed successfully');

        } catch (Exception $e) {

            return back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}

