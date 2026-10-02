<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

// Handles everything to do with managing products: list, create, view,
// edit, delete, and a simple search. This controller only talks to the
// Product model — it never touches the "tests" table directly, keeping
// each controller focused on one responsibility.
class ProductController extends Controller
{
    /**
     * Show a list of all products, with an optional simple search.
     * Route: GET /products
     */
    public function index(Request $request): View
    {
        // Start building the query. We don't run it yet — Eloquent lets
        // us add conditions first, then execute with ->paginate() at
        // the end. This is the standard Laravel query builder pattern.
        $query = Product::with('productType');

        // If the user typed something into the search box, filter by
        // product_id OR product_code containing that text. This is the
        // simple "Search product" feature from the Product Management
        // module — the more advanced multi-field search across testing
        // records comes later, in the Advanced Search module.
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('product_id', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%");
            });
        }

        // paginate() automatically splits results into pages of 10 and
        // generates the page-number links for us — no manual LIMIT/OFFSET
        // SQL needed.
        $products = $query->latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     * Route: GET /products/create
     */
    public function create(): View
    {
        // The create form needs a dropdown of product types, so we
        // fetch them all here and pass them to the view.
        $productTypes = ProductType::orderBy('name')->get();

        return view('products.create', compact('productTypes'));
    }

    /**
     * Store a newly submitted product.
     * Route: POST /products
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming form data before touching the database.
        // - product_id must be exactly 10 characters and unique, since
        //   the SRS requires a unique 10-digit code from the
        //   manufacturing unit.
        // - exists:product_types,id makes sure the selected product
        //   type is a real row in the product_types table (protects
        //   against a tampered form submitting a fake ID).
        $validated = $request->validate([
            'product_type_id' => 'required|exists:product_types,id',
            'product_id' => 'required|string|size:10|unique:products,product_id',
            'product_code' => 'required|string|size:4',
            'revision' => 'required|string|size:2',
            'manufacturing_number' => 'required|string|size:4',
            'status' => 'required|string|max:20',
        ]);

        // Create the product using only the validated data — never the
        // raw $request, so a malicious extra form field can't sneak
        // into the database.
        Product::create($validated);

        // Redirect back to the product list with a one-time "flash"
        // success message, available in the next request only.
        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show a single product's details, including its testing history.
     * Route: GET /products/{product}
     */
    public function show(Product $product): View
    {
        // Route model binding: Laravel automatically finds the Product
        // whose id matches {product} in the URL, or throws a 404 if it
        // doesn't exist — we never have to write Product::findOrFail()
        // ourselves.

        // Load the product's test history (with its test type) so the
        // product details page can show a mini testing history table.
        $product->load(['productType', 'tests.testType']);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing an existing product.
     * Route: GET /products/{product}/edit
     */
    public function edit(Product $product): View
    {
        $productTypes = ProductType::orderBy('name')->get();

        return view('products.edit', compact('product', 'productTypes'));
    }

    /**
     * Update an existing product.
     * Route: PUT/PATCH /products/{product}
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        // Same validation as store(), except the unique rule must
        // ignore the current product's own row — otherwise a product
        // would fail validation against its own existing product_id
        // every time it's edited without changing that field.
        $validated = $request->validate([
            'product_type_id' => 'required|exists:product_types,id',
            'product_id' => 'required|string|size:10|unique:products,product_id,' . $product->id,
            'product_code' => 'required|string|size:4',
            'revision' => 'required|string|size:2',
            'manufacturing_number' => 'required|string|size:4',
            'status' => 'required|string|max:20',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Delete a product.
     * Route: DELETE /products/{product}
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Recall from the migration: tests.product_id uses
        // cascadeOnDelete(). That means deleting a product here will
        // also delete all of its testing records automatically at the
        // database level — Laravel doesn't need extra code for that.
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
