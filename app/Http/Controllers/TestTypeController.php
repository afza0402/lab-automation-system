<?php

namespace App\Http\Controllers;

use App\Models\TestType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

// Manages the list of available test types (Electrical Test, Voltage
// Test, etc.). This module is intentionally small — the SRS just asks
// for these to live in the database instead of being hard-coded, so
// new test types can be added without touching any code.
class TestTypeController extends Controller
{
    /**
     * List all test types.
     * Route: GET /test-types
     */
    public function index(): View
    {
        $testTypes = TestType::orderBy('name')->get();

        return view('test_types.index', compact('testTypes'));
    }

    /**
     * Show the form for creating a new test type.
     * Route: GET /test-types/create
     */
    public function create(): View
    {
        return view('test_types.create');
    }

    /**
     * Store a newly submitted test type.
     * Route: POST /test-types
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:test_types,name',
            'test_code' => 'required|string|max:2|unique:test_types,test_code',
        ]);

        TestType::create($validated);

        return redirect()->route('test-types.index')
            ->with('success', 'Test type created successfully.');
    }

    /**
     * Show the form for editing an existing test type.
     * Route: GET /test-types/{test_type}/edit
     */
    public function edit(TestType $testType): View
    {
        return view('test_types.edit', compact('testType'));
    }

    /**
     * Update an existing test type.
     * Route: PUT/PATCH /test-types/{test_type}
     */
    public function update(Request $request, TestType $testType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:test_types,name,' . $testType->id,
            'test_code' => 'required|string|max:2|unique:test_types,test_code,' . $testType->id,
        ]);

        $testType->update($validated);

        return redirect()->route('test-types.index')
            ->with('success', 'Test type updated successfully.');
    }

    /**
     * Delete a test type.
     * Route: DELETE /test-types/{test_type}
     */
    public function destroy(TestType $testType): RedirectResponse
    {
        try {
            $testType->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('test-types.index')
                ->with('error', 'Cannot delete this test type — it is already used in existing testing records.');
        }

        return redirect()->route('test-types.index')
            ->with('success', 'Test type deleted successfully.');
    }
}