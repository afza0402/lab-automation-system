<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// A ProductType represents a category of manufactured product,
// e.g. "Switch Gear", "Fuse", "Capacitor".
class ProductType extends Model
{
    // Which columns are allowed to be mass-assigned via create()/update().
    // We list this explicitly (instead of using $guarded = []) so it's
    // obvious in the code exactly which fields a form is allowed to set —
    // this is a deliberate, explainable security choice for the viva.
    protected $fillable = ['name'];

    /**
     * A product type can have many products belonging to it.
     * Example: "Fuse" -> [Fuse #1001, Fuse #1002, Fuse #1003]
     *
     * This is the "one" side of the one-to-many relationship.
     * Laravel automatically looks for a "product_type_id" column
     * on the products table to make this connection.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}



