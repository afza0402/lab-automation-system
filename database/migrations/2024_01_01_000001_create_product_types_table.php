<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration creates the "product_types" table.
// It is a simple lookup table that lets us group products by category
// (e.g. Switch Gear, Fuse, Capacitor, Resistor) as required by the SRS,
// which says the application should be organised "based on the type
// of the product".
return new class extends Migration
{
    /**
     * Run the migration — this is what actually creates the table.
     */
    public function up(): void
    {
        Schema::create('product_types', function (Blueprint $table) {
            // Laravel's default auto-incrementing primary key (id).
            // This is the internal ID other tables will use to link
            // back to a product type via a foreign key.
            $table->id();

            // The category name, e.g. "Switch Gear".
            // unique() stops us from accidentally creating the same
            // product type twice under a different spelling/casing check.
            $table->string('name', 100)->unique();

            // created_at and updated_at timestamps, added automatically.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration — drops the table if we ever roll back.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_types');
    }
};
