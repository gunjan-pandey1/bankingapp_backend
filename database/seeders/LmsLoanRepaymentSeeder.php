<?php

namespace Database\Seeders;

use App\Models\LmsLoanRepayment;
use Illuminate\Database\Seeder;

class LmsLoanRepaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LmsLoanRepayment::factory()->count(50)->create();
    }
}
