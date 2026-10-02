@extends('layouts.app')
@section('content')
<h2>Product Details</h2>

<table class="table table-bordered w-auto">
    <tr><th>Product ID</th><td>{{ $product->product_id }}</td></tr>
        <tr><th>Product Type</th><td>{{ $product->productType?->name }}</td></tr>
        <tr><th>Product Code</th><td>{{ $product->product_code }}</td></tr>
        <tr><th>Revision</th><td>{{ $product->revision }}</td></tr>
        <tr><th>Manufacturing Number</th><td>{{ $product->manufacturing_number }}</td></tr>
        <tr><th>Status</th><td>{{ $product->status }}</td></tr>
</table>

    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning mb-4">Edit Product</a>
    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-4">Back to List</a>

    <h4>Testing History</h4>

    <table class="table table-bordered table-striped">
        <tr>
            <th>Testing ID</th>
            <th>Test Type</th>
            <th>Result</th>
            <th>Status</th>
            <th>Test Date</th>
        </tr>
        <tbody>
    @forelse ($product->tests as $test)
        <tr>
            <td>{{ $test->testing_id }}</td>
            <td>{{ $test->testType?->name }}</td>
            <td>{{ $test->result }}</td>
            <td>{{ $test->status }}</td>
            <td>{{ $test->test_date?->format('Y-m-d') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">
                No testing records yet for this product.
            </td>
        </tr>
    @endforelse
</tbody>
    </table>
@endsection