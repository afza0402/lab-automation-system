<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\View\View;

// The SRS specifically asks for users to be able to look records up by
// Product ID or Testing ID, plus a general "Advanced Search" option.
// This controller handles both in one place: it shows the search form,
// and if any filters were submitted, runs a filtered query and shows
// the matching testing records.
class SearchController extends Controller
{
    /**
     * Route: GET /search
     */
    public function index(Request $request): View
    {
        // Test types are needed for the "Test Type" filter dropdown.
        $testTypes = TestType::orderBy('name')->get();

        // Start from a base query. We only actually run it if the user
        // has submitted at least one filter — otherwise we'd show every
        // testing record in the system on first page load, which isn't
        // useful for a "search" page.
        $results = null;

        if ($request->hasAny([
            'product_id', 'testing_id', 'product_code', 'test_type_id',
            'result', 'status', 'tested_by', 'date_from', 'date_to',
        ])) {
            $query = Test::with(['product', 'testType']);

            // --- Filters that live directly on the tests table ---

            // 'like' with wildcards on both sides lets the user search
            // by a partial Testing ID, not just the exact full code.
            if ($request->filled('testing_id')) {
                $query->where('testing_id', 'like', '%' . $request->input('testing_id') . '%');
            }

            if ($request->filled('test_type_id')) {
                $query->where('test_type_id', $request->input('test_type_id'));
            }

            if ($request->filled('result')) {
                $query->where('result', $request->input('result'));
            }

            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }

            if ($request->filled('tested_by')) {
                $query->where('tested_by', 'like', '%' . $request->input('tested_by') . '%');
            }

            // Date RANGE filter: both ends are optional, so a user can
            // search "from this date onward" or "up to this date" or
            // both together.
            if ($request->filled('date_from')) {
                $query->whereDate('test_date', '>=', $request->input('date_from'));
            }
            if ($request->filled('date_to')) {
                $query->whereDate('test_date', '<=', $request->input('date_to'));
            }

            // --- Filters that live on the RELATED products table ---
            // Product ID and Product Code aren't columns on the tests
            // table itself, so we use whereHas() to filter tests based
            // on a condition matched against their related product.
            // This translates to a SQL subquery/join behind the scenes,
            // but Eloquent lets us express it in plain, readable PHP.
            if ($request->filled('product_id')) {
                $productId = $request->input('product_id');
                $query->whereHas('product', function ($q) use ($productId) {
                    $q->where('product_id', 'like', '%' . $productId . '%');
                });
            }

            if ($request->filled('product_code')) {
                $productCode = $request->input('product_code');
                $query->whereHas('product', function ($q) use ($productCode) {
                    $q->where('product_code', 'like', '%' . $productCode . '%');
                });
            }

            $results = $query->latest()->paginate(10)->withQueryString();
            // withQueryString() keeps the filter values in the URL when
            // moving between pages of results, so pagination links
            // don't silently lose the search that was just run.
        }

        return view('search.index', compact('testTypes', 'results'));
    }
}
