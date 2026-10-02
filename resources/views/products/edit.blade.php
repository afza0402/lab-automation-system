@extends('layouts.app')

@section('content')
    <h2>Edit Product</h2>

    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        {{-- Laravel has no native PUT/PATCH support in HTML forms, so
             @method('PUT') adds a hidden field telling Laravel to treat
             this POST as the "update" route instead of "store". --}}
        @method('PUT')

        @include('products._form')

        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
