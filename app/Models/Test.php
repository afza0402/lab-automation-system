<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// A Test represents a single testing record: one product, subjected to
// one type of test, on one date, with a result and a status.
class Test extends Model
{
    protected $fillable = [
        'testing_id',
        'product_id',
        'test_type_id',
        'testing_criteria',
        'expected_output',
        'actual_output',
        'result',
        'status',
        'tested_by',
        'test_date',
        'remarks',
    ];

    protected $casts = [
        'test_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function testType(): BelongsTo
    {
        return $this->belongsTo(TestType::class);
    }

    /**
     * Generate the unique 12-digit Testing ID for a new testing record.
     *
     * Format (finalized in Stage 2):
     *   [Product Code: 4][Revision: 2][Test Code: 2][Roll Number: 4] = 12 digits
     *
     * The roll number counts how many times THIS test type has already
     * been run on THIS specific product, then adds 1. For example, the
     * 1st Electrical Test on product ABCD/rev 01 becomes roll number
     * 0001 -> Testing ID "ABCD01ET0001". A 2nd Electrical Test on the
     * same product becomes "ABCD01ET0002". A Voltage Test on the same
     * product starts its OWN counter at "ABCD01VT0001", because the
     * count is scoped to product + test type together, not the product
     * alone.
     *
     * IMPORTANT: this method must always be called from inside a
     * DB::transaction() in the controller, with lockForUpdate() below.
     * Here's why that matters (a real viva-worthy detail):
     *
     * Without a lock, imagine two testing records for the SAME product
     * and test type are submitted at almost the exact same moment.
     * Both requests could read "count = 5" before either one saves,
     * and both would then generate roll number 0006 — a duplicate
     * Testing ID, which violates our unique() constraint from Stage 4
     * and would crash with a database error.
     *
     * lockForUpdate() tells MySQL: "while I'm counting these rows,
     * block any other transaction from counting or inserting into this
     * same set until I'm done." The second request then waits, counts
     * AFTER the first one has committed, and correctly gets 0007
     * instead of colliding on 0006.
     */
    public static function generateTestingId(Product $product, TestType $testType): string
    {
        $lastId = self::where('product_id', $product->id)
            ->where('test_type_id', $testType->id)
            ->lockForUpdate()
            ->orderByDesc('id')
            ->value('testing_id');

        $nextRollNumber = $lastId ? ((int) substr($lastId, -4)) + 1 : 1;

        // Our roll number field is only 4 digits wide, so the highest
        // value it can represent is 9999. This is an intentional,
        // explainable limit rather than a silent bug: if a single
        // product + test type combination is ever tested 9,999 times,
        // we stop rather than silently producing a broken/duplicate ID.
        // In a real lab this would essentially never happen, but a
        // beginner project should still show awareness of the boundary.
        if ($nextRollNumber > 9999) {
            throw new \RuntimeException(
                'Maximum of 9999 tests reached for this product and test type combination.'
            );
        }

        // str_pad fills the number with leading zeros until it is
        // exactly 4 characters wide, e.g. 7 becomes "0007".
        $rollNumber = str_pad($nextRollNumber, 4, '0', STR_PAD_LEFT);

        return $product->product_code . $product->revision . $testType->test_code . $rollNumber;
    }
}
