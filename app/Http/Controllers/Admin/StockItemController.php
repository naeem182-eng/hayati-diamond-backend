<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockItemRequest;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\GoldPrice;
use Illuminate\Http\Request;

class StockItemController extends Controller
{
    public function index(Request $request)
    {
        // รับค่าสำหรับการ Sorting & Filtering
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');
        $search = $request->get('search');
        $status = $request->get('status');

        // สร้าง Query และ Join กับ Products เพื่อให้ Sort ตามชื่อสินค้าได้
        $query = StockItem::query()
            ->select('stock_items.*')
            ->join('products', 'stock_items.product_id', '=', 'products.id');

        // Logic การกรอง (Filter)
        if ($request->filled('search')) {
            $query->where('products.name', 'like', '%' . $search . '%');
        }
        if ($request->filled('status')) {
            $query->where('stock_items.status', $status);
        }

        // Logic การเรียงลำดับ (Sorting)
        if ($sort == 'product_name') {
            $query->orderBy('products.name', $direction);
        } else {
            $query->orderBy('stock_items.' . $sort, $direction);
        }

        if ($sort == 'product_name') {
            $query->orderBy('products.name', $direction);
        } elseif (in_array($sort, ['serial_no', 'ring_size'])) {

        // เพิ่มให้รองรับการ Sort สองคอลัมน์ใหม่
            $query->orderBy('stock_items.' . $sort, $direction);
        } else {
            $query->orderBy('stock_items.' . $sort, $direction);
        }

        // ดึงราคาทองล่าสุดมาแสดงผล
        $currentGoldPrice = GoldPrice::orderBy('effective_date', 'desc')->first()->price_per_gram ?? 0;

        // ดึงข้อมูลพร้อมรักษาค่า Query String ใน Link หน้าถัดไป
        $stockItems = $query->with(['product', 'invoiceItem'])->paginate(20)->withQueryString();

        return view('admin.stock-items.index', compact('stockItems', 'sort', 'direction', 'currentGoldPrice'));
    }

    public function edit(StockItem $stockItem)
    {
        $products = Product::where('is_active', true)->get();
        return view('admin.stock-items.edit', compact('stockItem', 'products'));
    }

    public function update(StockItemRequest $request, StockItem $stockItem)
    {
        $stockItem->update($request->validated());
        return redirect()->route('admin.stock-items.index')->with('success', 'Stock item updated');
    }

    public function destroy(StockItem $stockItem)
    {
        $stockItem->delete();
        return redirect()->route('admin.stock-items.index')->with('success', 'Stock item deleted');
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
