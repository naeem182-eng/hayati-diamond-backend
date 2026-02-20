<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoldPrice;
use Illuminate\Http\Request;

class GoldPriceController extends Controller
{
    public function create()
    {
        $latest = GoldPrice::orderByDesc('effective_date')->first();

        return view('admin.gold-prices.create', compact('latest'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'price_per_gram' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
        ]);

        GoldPrice::create([
            'price_per_gram' => $request->price_per_gram,
            'effective_date' => $request->effective_date,
        ]);

        return back()->with('success', 'Gold price updated successfully.');
    }
}
