<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockItemRequest;
use App\Models\Product;
use App\Models\StockItem;

class StockItemController extends Controller
{
    public function index()
    {
        $stockItems = StockItem::with('product')
            ->latest()
            ->paginate(20);

        return view('admin.stock-items.index', compact('stockItems'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->get();
        return view('admin.stock-items.create', compact('products'));
    }

    public function store(StockItemRequest $request)
    {
        StockItem::create([
            'product_id' => $request->product_id,
            'serial_no' => $request->serial_no,
            'ring_size' => $request->ring_size,
            'gold_weight_actual' => $request->gold_weight_actual,
            'gold_price_at_make' => $request->gold_price_at_make,
            'diamond_detail' => $request->diamond_detail,
            'total_cost' => $request->total_cost,
            'status' => StockItem::STATUS_IN_STOCK,
        ]);

        return redirect()
            ->route('admin.stock-items.index')
            ->with('success', 'Stock item created successfully');
    }
}
