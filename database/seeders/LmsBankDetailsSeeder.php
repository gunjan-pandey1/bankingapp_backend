<?php

namespace Database\Seeders;

use App\Models\LmsBankDetails;
use Illuminate\Database\Seeder;

class LmsBankDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LmsBankDetails::factory()->count(50)->create();
    }
}
