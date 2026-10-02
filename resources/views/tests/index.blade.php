@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Testing Records</h2>
        <a href="{{ route('tests.create') }}" class="btn btn-primary">+ Add Testing Record</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Testing ID</th>
                <th>Product</th>
                <th>Test Type</th>
                <th>Result</th>
                <th>Status</th>
                <th>Test Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tests as $test)
                <tr>
                    <td>{{ $test->testing_id }}</td>
                    {{-- $test->product and $test->testType were eager
                         loaded in the controller with with([...]) —
                         accessing them here does NOT trigger extra
                         database queries. --}}
                    <td>{{ $test->product?->product_id }}</td>
                    <td>{{ $test->testType?->name }}</td>
                    <td>
                        {{-- A small visual cue: green badge for Pass,
                             red for Fail. Purely cosmetic Bootstrap
                             classes, no extra logic needed. --}}
                        <span class="badge {{ $test->result === 'Pass' ? 'bg-success' : 'bg-danger' }}">
                            {{ $test->result }}
                        </span>
                    </td>
                    <td>{{ $test->status }}</td>
                    <td>{{ $test->test_date?->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('tests.show', $test) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('tests.edit', $test) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('tests.destroy', $test) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this testing record?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No testing records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $tests->links() }}
@endsection
