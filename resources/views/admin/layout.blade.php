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
        <ul>
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.products.index') }}">Products</a></li>
            <li><a href="{{ route('admin.stock-items.index') }}">Stock</a></li>
            <li><a href="{{ route('admin.sales.create') }}">Sale</a></li>
            <li><a href="{{ route('admin.installments.index') }}">🧾 บิลผ่อนค้าง</a></li>
        </ul>
    </aside>

    <main style="padding:20px; flex:1">
        @yield('content')
    </main>

</div>
</body>

</html>
