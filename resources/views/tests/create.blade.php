@extends('layouts.app')

@section('content')
    <h2>Add Testing Record</h2>

    <div class="alert alert-info">
        The Testing ID will be generated automatically once you save this record.
    </div>

    {{-- New in Stage 9: shows the RuntimeException message from
         generateTestingId() if the 9999-roll-number limit is ever hit. --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('tests.store') }}">
        @csrf
        @include('tests._form')

        <button type="submit" class="btn btn-primary">Save Testing Record</button>
        <a href="{{ route('tests.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
