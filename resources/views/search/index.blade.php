@extends('layouts.app')

@section('content')
    <h2>Advanced Search</h2>

    {{-- method="GET" is deliberate: search filters belong in the URL
         (not a POST body), so a search can be bookmarked, refreshed,
         or shared with a colleague, and pagination can carry the same
         filters forward via withQueryString() in the controller. --}}
    <form method="GET" action="{{ route('search.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Product ID</label>
            <input type="text" name="product_id" class="form-control" value="{{ request('product_id') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Testing ID</label>
            <input type="text" name="testing_id" class="form-control" value="{{ request('testing_id') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Product Code</label>
            <input type="text" name="product_code" class="form-control" value="{{ request('product_code') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Test Type</label>
            <select name="test_type_id" class="form-select">
                <option value="">-- Any --</option>
                @foreach ($testTypes as $testType)
                    <option value="{{ $testType->id }}" @selected(request('test_type_id') == $testType->id)>
                        {{ $testType->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Result</label>
            <select name="result" class="form-select">
                <option value="">-- Any --</option>
                @foreach (['Pass', 'Fail'] as $option)
                    <option value="{{ $option }}" @selected(request('result') == $option)>{{ $option }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">-- Any --</option>
                @foreach (['Pending', 'In Progress', 'Completed'] as $option)
                    <option value="{{ $option }}" @selected(request('status') == $option)>{{ $option }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Tested By</label>
            <input type="text" name="tested_by" class="form-control" value="{{ request('tested_by') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">&nbsp;</label>
            <div></div>
        </div>

        <div class="col-md-3">
            <label class="form-label">Date From</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Date To</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('search.index') }}" class="btn btn-secondary">Clear</a>
        </div>
    </form>

    {{-- $results stays null until the user actually submits at least
         one filter (see the controller) — this is what avoids dumping
         every testing record on the page before a search is run. --}}
    @if ($results !== null)
        <h5>{{ $results->total() }} result(s) found</h5>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Testing ID</th>
                    <th>Product</th>
                    <th>Test Type</th>
                    <th>Result</th>
                    <th>Status</th>
                    <th>Tested By</th>
                    <th>Test Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($results as $test)
                    <tr>
                        <td>{{ $test->testing_id }}</td>
                        <td>{{ $test->product?->product_id }}</td>
                        <td>{{ $test->testType?->name }}</td>
                        <td>{{ $test->result ?? '—' }}</td>
                        <td>{{ $test->status }}</td>
                        <td>{{ $test->tested_by }}</td>
                        <td>{{ $test->test_date?->format('Y-m-d') }}</td>
                        <td><a href="{{ route('tests.show', $test) }}" class="btn btn-sm btn-info">View</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No matching testing records.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $results->links() }}
    @endif
@endsection
