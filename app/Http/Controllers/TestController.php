<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Test;
use App\Models\TestType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TestController extends Controller
{
    public function index(): View
    {
        $tests = Test::with(['product', 'testType'])
            ->latest()
            ->paginate(10);

        return view('tests.index', compact('tests'));
    }

    public function create(): View
    {
        $products = Product::orderBy('product_id')->get();
        $testTypes = TestType::orderBy('name')->get();

        return view('tests.create', compact('products', 'testTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTest($request);

        $product = Product::findOrFail($validated['product_id']);
        $testType = TestType::findOrFail($validated['test_type_id']);

        try {
            $test = DB::transaction(function () use ($validated, $product, $testType) {
                $validated['testing_id'] = Test::generateTestingId($product, $testType);

                $test = Test::create($validated);

                // New in Stage 10: as soon as a result exists, reflect
                // it on the product itself. See Product::syncStatusFromResult()
                // for exactly what this changes and why.
                $product->syncStatusFromResult($test->result);

                return $test;
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('tests.index')
            ->with('success', "Testing record created with Testing ID: {$test->testing_id}");
    }

    public function show(Test $test): View
    {
        $test->load(['product', 'testType']);

        return view('tests.show', compact('test'));
    }

    public function edit(Test $test): View
    {
        $products = Product::orderBy('product_id')->get();
        $testTypes = TestType::orderBy('name')->get();

        return view('tests.edit', compact('test', 'products', 'testTypes'));
    }

    public function update(Request $request, Test $test): RedirectResponse
    {
        $validated = $this->validateTest($request);

        unset($validated['product_id'], $validated['test_type_id']);

        $test->update($validated);

        // The result may have changed during a full edit too (e.g. a
        // technician correcting an earlier Pending record to Fail),
        // so we sync the product's status here as well, not just on
        // initial creation.
        $test->product->syncStatusFromResult($test->result);

        return redirect()->route('tests.index')
            ->with('success', 'Testing record updated successfully.');
    }

    /**
     * Quickly move a testing record's status forward
     * (Pending -> In Progress -> Completed) without opening the full
     * edit form. This is the "status tracking" workflow feature —
     * separate from the general-purpose edit form, because moving a
     * record along its workflow is something staff will do often and
     * shouldn't require re-typing every field each time.
     *
     * Route: PATCH /tests/{test}/status
     */
    public function updateStatus(Request $request, Test $test): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
            // result is only ever submitted from this quick-update form
            // when the technician is marking the test Completed.
            'result' => 'nullable|required_if:status,Completed|in:Pass,Fail',
        ]);

        $test->update($validated);
        $test->product->syncStatusFromResult($test->result);

        return redirect()->route('tests.show', $test)
            ->with('success', 'Status updated.');
    }

    public function destroy(Test $test): RedirectResponse
    {
        $test->delete();

        return redirect()->route('tests.index')
            ->with('success', 'Testing record deleted successfully.');
    }

    /**
     * Shared validation rules for store() and update().
     * Pulled into one method so both actions always stay in sync —
     * previously they were two separate copies of the same rules.
     *
     * New in Stage 10: result is only REQUIRED once status is
     * "Completed" (required_if). A test that's still Pending or In
     * Progress simply hasn't produced a result yet, so forcing
     * Pass/Fail to be picked immediately no longer makes sense.
     */
    private function validateTest(Request $request): array
    {
        return $request->validate([
            'product_id' => 'required|exists:products,id',
            'test_type_id' => 'required|exists:test_types,id',
            'testing_criteria' => 'required|string|max:255',
            'expected_output' => 'required|string|max:255',
            'actual_output' => 'required|string|max:255',
            'result' => 'nullable|required_if:status,Completed|in:Pass,Fail',
            'status' => 'required|in:Pending,In Progress,Completed',
            'tested_by' => 'required|string|max:150',
            'test_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);
    }
}
