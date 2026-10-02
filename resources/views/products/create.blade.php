@extends('layouts.app')

@section('content')
    <h2>Add Product</h2>

    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        {{-- Pulls in the shared field partial. $product is undefined
             here, which is fine — every ?? '' fallback in the partial
             handles that for a "create" form. --}}
        @include('products._form')

        <button type="submit" class="btn btn-primary">Save Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
