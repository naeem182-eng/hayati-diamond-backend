@extends('admin.layout')

@section('content')
<h1>Edit Product</h1>

<form method="POST" action="{{ route('admin.products.update', $product) }}">
    @csrf @method('PUT')
    @include('admin.products._form')
</form>
@endsection
