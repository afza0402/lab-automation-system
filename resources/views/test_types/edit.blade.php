@extends('layouts.app')

@section('content')
    <h2>Edit Test Type</h2>

    <form method="POST" action="{{ route('test-types.update', $testType) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $testType->name) }}" required>
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Test Code (2 characters, used in Testing ID)</label>
            <input type="text" name="test_code" maxlength="2" class="form-control" value="{{ old('test_code', $testType->test_code) }}" required>
            @error('test_code') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Test Type</button>
        <a href="{{ route('test-types.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
