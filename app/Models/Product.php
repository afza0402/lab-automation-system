<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'product_type_id',
        'product_id',
        'product_code',
        'revision',
        'manufacturing_number',
        'status',
    ];

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

    /**
     * Update this product's own status based on a testing result.
     *
     * This is what makes "Product/testing status can be tracked"
     * (a step in the SRS's expected workflow) actually visible: a
     * product's status reflects the outcome of its most recent test,
     * not just whatever was typed in when the product was created.
     *
     *   Fail -> product marked "Failed"   (heading for re-manufacturing)
     *   Pass -> product marked "Active"   (cleared, can proceed further)
     *
     * We deliberately do NOT touch product status when result is still
     * null (test is Pending/In Progress) — the product's status should
     * only change once a test has actually concluded.
     */
    public function syncStatusFromResult(?string $result): void
    {
        if ($result === 'Fail') {
            $this->update(['status' => 'Failed']);
        } elseif ($result === 'Pass') {
            $this->update(['status' => 'Active']);
        }
        // If $result is null (still Pending/In Progress), we leave the
        // product's status exactly as it is.
    }
}
