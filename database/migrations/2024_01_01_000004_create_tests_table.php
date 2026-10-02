<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration creates the "tests" table — the most important table
// in the whole system. Every testing record a lab technician enters
// lives here, linked back to the product being tested and the type
// of test performed.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();

            // The auto-generated 12-digit Testing ID.
            // Generated in code (Stage 9), never typed in by the user.
            // unique() enforces that no two testing records can ever
            // share the same Testing ID.
            $table->string('testing_id', 12)->unique();

            // Foreign key to the product being tested.
            // cascadeOnDelete() means if a product is ever deleted, its
            // testing history is deleted with it — this keeps the
            // database consistent (no "orphan" test records pointing
            // at a product that no longer exists).
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();

            // Foreign key to the type of test performed.
            // restrictOnDelete() here instead of cascade, because
            // deleting a test type should NOT silently delete real
            // testing history — that data is too important to lose
            // by accident.
            $table->foreignId('test_type_id')
                  ->constrained('test_types')
                  ->restrictOnDelete();

            // What condition/spec the product is being tested against.
            $table->string('testing_criteria', 255);

            // What result was expected vs what was actually observed.
            $table->string('expected_output', 255);
            $table->string('actual_output', 255);

            // The final outcome of the test: Pass or Fail.
            // Kept separate from "status" (below) because result answers
            // "did it work?" while status answers "where is this record
            // in the workflow?" — two different questions.
            $table->string('result', 10)->nullable();

            // Where this testing record currently is in the workflow:
            // Pending, In Progress, or Completed.
            $table->string('status', 20)->default('Pending');

            // Name(s) of the person(s) who performed the test.
            // The SRS explicitly says "person(s)", so a simple text field
            // that can hold one or more comma-separated names is enough —
            // we don't need a separate testers table for this project.
            $table->string('tested_by', 150);

            // The date the test was performed.
            $table->date('test_date');

            // Free-form notes about the testing record.
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
