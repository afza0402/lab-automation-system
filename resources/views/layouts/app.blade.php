<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Automation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

@auth
{{-- A single top navbar rather than a sidebar. The SRS doesn't ask
     for a specific navigation style, and a sidebar would mean extra
     CSS/layout work for no functional benefit — a top navbar is the
     simplest structure Bootstrap gives us for free. --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3 mb-4">
    <a class="navbar-brand" href="{{ route('dashboard') }}">Lab Automation System</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav me-auto">
            {{-- request()->routeIs() checks the CURRENT route name and
                 lets us highlight whichever nav link matches the page
                 the user is actually on — a small, honest use of
                 "active" state without any extra JavaScript. --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}"
                   href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('products.*') ? 'active fw-bold' : '' }}"
                   href="{{ route('products.index') }}">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('test-types.*') ? 'active fw-bold' : '' }}"
                   href="{{ route('test-types.index') }}">Test Types</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tests.*') ? 'active fw-bold' : '' }}"
                   href="{{ route('tests.index') }}">Testing Records</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('search.*') ? 'active fw-bold' : '' }}"
                   href="{{ route('search.index') }}">Advanced Search</a>
            </li>
        </ul>

        <form method="POST" action="{{ route('logout') }}" class="d-flex">
            @csrf
            <span class="navbar-text text-light me-3">{{ auth()->user()->name }}</span>
            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
        </form>
    </div>
</nav>
@endauth

<div class="container pb-5">
    @yield('content')
</div>

{{-- Bootstrap's JS bundle is only needed for the navbar's mobile
     toggle button (data-bs-toggle) above — everything else on the
     site is plain HTML forms and Blade, no JS required. --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
