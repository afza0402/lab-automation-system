<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Test;
use Illuminate\View\View;

// The dashboard is a supporting feature, not a core SRS requirement,
// so it's kept intentionally small: a handful of real counts pulled
// straight from MySQL, plus a short list of the most recent testing
// records. No charts, no hard-coded numbers.
class DashboardController extends Controller
{
    /**
     * Route: GET /dashboard
     */
    public function index(): View
    {
        // Each of these is a single, simple Eloquent query. count()
        // runs a SQL COUNT(*) query rather than loading every row into
        // PHP just to count them — an important distinction once the
        // tables have thousands of records.
        $totalProducts = Product::count();
        $totalTests = Test::count();
        $passedTests = Test::where('result', 'Pass')->count();
        $failedTests = Test::where('result', 'Fail')->count();

        // "Pending" here means anything not yet Completed, which
        // includes both the Pending and In Progress statuses — this
        // matches how the dashboard card is meant to read at a glance.
        $pendingTests = Test::where('status', '!=', 'Completed')->count();

        // Recent Testing Records: the last 5 created, with their
        // product and test type eager-loaded so the view doesn't fire
        // extra queries per row (same N+1 avoidance as Stage 8/9).
        $recentTests = Test::with(['product', 'testType'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalTests',
            'passedTests',
            'failedTests',
            'pendingTests',
            'recentTests',
        ));
    }
}
