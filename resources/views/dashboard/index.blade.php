@extends('layouts.app')

@section('content')
    <h2>Dashboard</h2>
    <p>Welcome, {{ auth()->user()->name }}.</p>

    {{-- Six simple Bootstrap cards, each showing one number passed in
         from DashboardController@index. No charts, no JavaScript —
         the SRS never asked for visual analytics, just real counts. --}}
    <div class="row g-3 mb-4">
        <div class="col-md-2 col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6>Total Products</h6>
                    <h3>{{ $totalProducts }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6>Total Tests</h6>
                    <h3>{{ $totalTests }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4">
            <div class="card text-center bg-success-subtle">
                <div class="card-body">
                    <h6>Passed</h6>
                    <h3>{{ $passedTests }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4">
            <div class="card text-center bg-danger-subtle">
                <div class="card-body">
                    <h6>Failed</h6>
                    <h3>{{ $failedTests }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4">
            <div class="card text-center bg-warning-subtle">
                <div class="card-body">
                    <h6>Pending / In Progress</h6>
                    <h3>{{ $pendingTests }}</h3>
                </div>
            </div>
        </div>
    </div>

    <h5>Recent Testing Records</h5>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Testing ID</th>
                <th>Product</th>
                <th>Test Type</th>
                <th>Result</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentTests as $test)
                <tr>
                    <td>{{ $test->testing_id }}</td>
                    <td>{{ $test->product?->product_id }}</td>
                    <td>{{ $test->testType?->name }}</td>
                    <td>{{ $test->result ?? '—' }}</td>
                    <td>{{ $test->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No testing records yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
