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

    <aside style="width:200px; padding:10px; background:#f5f5f5">
    <h3>Hayati Admin</h3>
    <p style="font-size:14px; color:gray;">
    👤 {{ auth()->user()->name }}
    </p>


    <ul class="space-y-2">

    <li><a href="{{ route('admin.dashboard') }}">📊 Dashboard</a></li>
    <li><a href="{{ route('admin.gold-price.create') }}">🪙 Update Gold Price(18K)</a></li>
    <br>

    <h4>Sales</h4>
    <li><a href="{{ route('admin.sales.create') }}">➕ New Sale</a></li>
    <li><a href="{{ route('admin.payments.index') }}">💰 Payments</a></li>
    <li><a href="{{ route('admin.invoices.index') }}">📄 Invoices</a></li>
    <li><a href="{{ route('admin.installments.index') }}">📑 Installments</a></li>
    <br>

    <h4>Inventory</h4>
    <li><a href="{{ route('admin.products.index') }}">📦 Products</a></li>
    <li><a href="{{ route('admin.stock-items.index') }}">🏷 Stock</a></li>
    <br>

    <h4>CRM</h4>
    <li><a href="{{ route('admin.customers.index') }}">👤 Customers</a></li>

    </ul>

    <hr>
    <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" style="margin-top:10px;">
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
