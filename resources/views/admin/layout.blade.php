<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; }
        a { margin-right: 8px; }
    </style>
</head>
<body>
<div style="display:flex">

<aside style="min-width: fit-content; white-space: nowrap; padding: 20px; background: #f5f5f5; border-right: 1px solid #ddd; height: 100vh;">

    <h3>Hayati Admin</h3>
    <p style="font-size: 14px; color: gray; margin-bottom: 20px;">
        👤 {{ auth()->user()->name }}
    </p>
    <br>
    <ul style="list-style: none; padding: 0;">
        <li style="margin-bottom: 8px;"><a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: #333;">📊 Dashboard</a></li>
        <li style="margin-bottom: 8px;"><a href="{{ route('admin.gold-price.create') }}" style="text-decoration: none; color: #333;">🪙 Update Gold Price(18K)</a></li>
    <br>
        <h4 style="margin-top: 20px; color: #C8A951;">Sales</h4>
        <li style="margin-bottom: 8px;"><a href="{{ route('admin.sales.create') }}" style="text-decoration: none; color: #333;">➕ New Sale</a></li>
        <li style="margin-bottom: 8px;"><a href="{{ route('admin.payments.index') }}" style="text-decoration: none; color: #333;">💰 Payments</a></li>
        <li style="margin-bottom: 8px;"><a href="{{ route('admin.invoices.index') }}" style="text-decoration: none; color: #333;">📄 Invoices</a></li>
        <li style="margin-bottom: 8px;"><a href="{{ route('admin.installments.index') }}" style="text-decoration: none; color: #333;">📑 Installments</a></li>
    <br>
        <h4 style="margin-top: 20px; color: #C8A951;">Inventory</h4>
        <li style="margin-bottom: 8px;"><a href="{{ route('admin.products.index') }}" style="text-decoration: none; color: #333;">📦 Products</a></li>
        <li style="margin-bottom: 8px;"><a href="{{ route('admin.stock-items.index') }}" style="text-decoration: none; color: #333;">🏷 Stock</a></li>
    <br>
        <h4 style="margin-top: 20px; color: #C8A951;">CRM</h4>
        <li style="margin-bottom: 8px;"><a href="{{ route('admin.customers.index') }}" style="text-decoration: none; color: #333;">👤 Customers</a></li>
    </ul>
    <br>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="width: 100%; padding: 8px; cursor: pointer; background: #eee; border: 1px solid #ccc; border-radius: 4px;">
            🚪 Logout
        </button>
    </form>
</aside>

    <main style="padding:20px; flex:1">
        @yield('content')
    </main>

</div>
</body>

</html>
