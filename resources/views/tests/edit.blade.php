@extends('layouts.app')

@section('content')
    <h2>Edit Testing Record</h2>

    <div class="alert alert-secondary">
        Testing ID: <strong>{{ $test->testing_id }}</strong> (fixed — never changes after creation)
    </div>

    <form method="POST" action="{{ route('tests.update', $test) }}">
        @csrf
        @method('PUT')
        @include('tests._form')

        <button type="submit" class="btn btn-primary">Update Testing Record</button>
        <a href="{{ route('tests.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
