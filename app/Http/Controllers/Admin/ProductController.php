<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
    // รับค่าจาก URL ถ้าไม่มีให้ใช้ id และ desc เป็นค่าเริ่มต้น
    $sort = $request->get('sort', 'id');
    $direction = $request->get('direction', 'desc');

    $products = Product::orderBy($sort, $direction)->paginate(15);

    return view('admin.products.index', compact('products', 'sort', 'direction'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(ProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        // ❗ business-safe: ยังไม่เช็ค stockItems ก่อน (เพิ่มภายหลังได้)
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }
}
