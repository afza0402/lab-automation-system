<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Test;
use App\Models\TestType;
use App\Models\User;
use Illuminate\Database\Seeder;

// Seeders exist so you (or your instructor) can see the application
// working immediately with realistic-looking sample data, without
// manually typing everything through the UI first. Every value here
// is clearly fake/sample data, as the spec asks for.
//
// Order matters: we seed users and lookup tables (product_types,
// test_types) BEFORE products and tests, for the same reason the
// migrations had to run in a specific order back in Stage 4 — a
// product can't reference a product type that doesn't exist yet.
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1. Users (for logging in) ---
        $user = User::create([
            'name' => 'Lab Admin',
            'email' => 'admin@lab.test',
            // bcrypt() hashes the password before storing it — we
            // never store plain text passwords, even in a seeder.
            'password' => bcrypt('password'),
        ]);

        // --- 2. Product Types ---
        $switchGear = ProductType::create(['name' => 'Switch Gear']);
        $fuse = ProductType::create(['name' => 'Fuse']);
        $capacitor = ProductType::create(['name' => 'Capacitor']);

        // --- 3. Test Types ---
        $electrical = TestType::create(['name' => 'Electrical Test', 'test_code' => 'ET']);
        $voltage = TestType::create(['name' => 'Voltage Test', 'test_code' => 'VT']);
        $resistance = TestType::create(['name' => 'Resistance Test', 'test_code' => 'RT']);

        // --- 4. Products ---
        // product_id here follows our 10-digit format: 4-digit product
        // code + 2-digit revision + 4-digit manufacturing number.
        $product1 = Product::create([
            'product_type_id' => $switchGear->id,
            'product_id' => 'SWGR010001',
            'product_code' => 'SWGR',
            'revision' => '01',
            'manufacturing_number' => '0001',
            'status' => 'Active',
        ]);

        $product2 = Product::create([
            'product_type_id' => $fuse->id,
            'product_id' => 'FUSE020015',
            'product_code' => 'FUSE',
            'revision' => '02',
            'manufacturing_number' => '0015',
            'status' => 'Active',
        ]);

        $product3 = Product::create([
            'product_type_id' => $capacitor->id,
            'product_id' => 'CAPA010007',
            'product_code' => 'CAPA',
            'revision' => '01',
            'manufacturing_number' => '0007',
            'status' => 'Active',
        ]);

        // --- 5. Testing Records ---
        // We reuse the SAME generateTestingId() method the real
        // controller calls, so seeded records follow the exact same
        // 12-digit format instead of a separate hard-coded scheme.
        $this->createTest($product1, $electrical, 'Insulation resistance >= 2 MOhm', '>= 2 MOhm', '2.4 MOhm', 'Pass', 'Completed');
        $this->createTest($product1, $voltage, 'Withstand 440V for 60s', 'No breakdown', 'No breakdown observed', 'Pass', 'Completed');
        $this->createTest($product2, $electrical, 'Continuity check', 'Continuous', 'Open circuit detected', 'Fail', 'Completed');
        $this->createTest($product3, $resistance, 'Resistance within 5% tolerance', '100 Ohm +/-5%', '106 Ohm', 'Pass', 'Completed');
        $this->createTest($product2, $voltage, 'Withstand 250V for 30s', 'No breakdown', null, null, 'Pending');
    }

    /**
     * Small local helper so the 5 sample testing records above don't
     * repeat the same 6 lines of setup each time. Not a permanent part
     * of the application — just keeps this seeder file readable.
     */
    private function createTest(
        Product $product,
        TestType $testType,
        string $criteria,
        string $expected,
        ?string $actual,
        ?string $result,
        string $status
    ): void {
        Test::create([
            'testing_id' => Test::generateTestingId($product, $testType),
            'product_id' => $product->id,
            'test_type_id' => $testType->id,
            'testing_criteria' => $criteria,
            'expected_output' => $expected,
            'actual_output' => $actual ?? 'Not yet recorded',
            'result' => $result,
            'status' => $status,
            'tested_by' => 'Ahmed Khan',
            'test_date' => now()->subDays(rand(1, 10)),
            'remarks' => null,
        ]);
    }
}
