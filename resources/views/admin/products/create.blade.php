@extends('admin.layout')

@section('content')
<h1>Create Product</h1>

<form method="POST" action="{{ route('admin.products.store') }}">
    @csrf
    @include('admin.products._form')
</form>
@endsection
