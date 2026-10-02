<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Lab Automation System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container" style="max-width: 400px; margin-top: 100px;">

    <div class="card shadow-sm">

        <div class="card-body">

            <h4 class="card-title mb-3 text-center">
                Lab Automation System
            </h4>

            @error('email')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror

            <form method="POST" action="{{ route('login') }}">

                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Login
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>