@extends('admin.layout')

@section('content')
<h1>Products</h1>

<a href="{{ route('admin.products.create') }}">➕ New Product</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Active</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category }}</td>
            <td>{{ $product->is_active ? 'Yes' : 'No' }}</td>
            <td>
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" width="60">
                @endif
            </td>
            <td>
                <a href="{{ route('admin.products.edit', $product) }}">✏️ Edit</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Delete?')">🗑</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $products->links() }}
@endsection
