<?php

namespace Database\Seeders;

use App\Models\LmsLoan;
use Illuminate\Database\Seeder;

class LmsLoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LmsLoan::factory()->count(50)->create();
    }
}
