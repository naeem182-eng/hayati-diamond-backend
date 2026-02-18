<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(20);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'address' => 'nullable',
        ]);

        Customer::create($data);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer created');
    }

    public function show(Customer $customer)
    {
        $customer->load('invoices');

        $totalSpent = $customer->invoices->sum('total_amount');

        $totalOutstanding = $customer->invoices
            ->sum(fn($inv) => $inv->outstanding_balance);

        return view('admin.customers.show', compact(
            'customer',
            'totalSpent',
            'totalOutstanding'
        ));
    }
}
