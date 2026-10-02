@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Test Types</h2>
        <a href="{{ route('test-types.create') }}" class="btn btn-primary">+ Add Test Type</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- The "error" flash message is used here, separately from
         "success", so the delete-blocked message (from the
         restrictOnDelete() foreign key) is visually distinct. --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Test Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($testTypes as $testType)
                <tr>
                    <td>{{ $testType->name }}</td>
                    <td>{{ $testType->test_code }}</td>
                    <td>
                        <a href="{{ route('test-types.edit', $testType) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('test-types.destroy', $testType) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this test type?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No test types yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
