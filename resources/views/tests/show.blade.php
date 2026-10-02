@extends('layouts.app')

@section('content')
    <h2>Testing Record Details</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered w-auto">
        <tr><th>Testing ID</th><td>{{ $test->testing_id }}</td></tr>
        <tr><th>Product</th><td>{{ $test->product?->product_id }} ({{ $test->product?->product_code }})</td></tr>
        <tr><th>Test Type</th><td>{{ $test->testType?->name }}</td></tr>
        <tr><th>Testing Criteria</th><td>{{ $test->testing_criteria }}</td></tr>
        <tr><th>Expected Output</th><td>{{ $test->expected_output }}</td></tr>
        <tr><th>Actual Output</th><td>{{ $test->actual_output }}</td></tr>
        <tr>
            <th>Result</th>
            <td>
                @if ($test->result)
                    <span class="badge {{ $test->result === 'Pass' ? 'bg-success' : 'bg-danger' }}">{{ $test->result }}</span>
                @else
                    <span class="text-muted">Not yet determined</span>
                @endif
            </td>
        </tr>
        <tr><th>Status</th><td>{{ $test->status }}</td></tr>
        <tr><th>Tested By</th><td>{{ $test->tested_by }}</td></tr>
        <tr><th>Test Date</th><td>{{ $test->test_date?->format('Y-m-d') }}</td></tr>
        <tr><th>Remarks</th><td>{{ $test->remarks }}</td></tr>
    </table>

    {{-- New in Stage 10: quick status-tracking panel. This is a
         SEPARATE, smaller form from the full edit page — it exists so
         moving a record through Pending -> In Progress -> Completed
         doesn't require re-typing the criteria/outputs/remarks every
         time, just the two fields that actually change during the
         workflow. --}}
    <div class="card mb-4" style="max-width: 500px;">
        <div class="card-body">
            <h5 class="card-title">Update Status</h5>
            <form method="POST" action="{{ route('tests.updateStatus', $test) }}">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach (['Pending', 'In Progress', 'Completed'] as $option)
                            <option value="{{ $option }}" @selected($test->status == $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Result (required once Completed)</label>
                    <select name="result" class="form-select">
                        <option value="">-- Not yet determined --</option>
                        @foreach (['Pass', 'Fail'] as $option)
                            <option value="{{ $option }}" @selected($test->result == $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('result') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        </div>
    </div>

    <a href="{{ route('tests.edit', $test) }}" class="btn btn-warning">Full Edit</a>
    <a href="{{ route('tests.index') }}" class="btn btn-secondary">Back to List</a>
@endsection
