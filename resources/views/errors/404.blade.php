{{-- Laravel automatically renders THIS file for any 404 error, with
     no extra code required — it just has to exist at
     resources/views/errors/404.blade.php. This is what a beginner
     project gets "for free" from Laravel's conventions.

     This single page covers every "not found" case in the spec:
     - a Product ID that doesn't exist (products.show/edit/destroy)
     - a Testing ID/record that doesn't exist (tests.show/edit/destroy)
     Route model binding (used throughout every controller in this
     project) throws this same 404 automatically whenever {product}
     or {test} in a URL doesn't match a real row — we never had to
     write "if not found" checks ourselves. --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="text-center">
        <h1 class="display-4">404</h1>
        <p class="lead">The record you're looking for doesn't exist or may have been deleted.</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary">Go Back</a>
    </div>
</body>
</html>
