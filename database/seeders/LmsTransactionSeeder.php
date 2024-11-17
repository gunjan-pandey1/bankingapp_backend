<?php

namespace Database\Seeders;

use App\Models\LmsTransaction;
use Illuminate\Database\Seeder;

class LmsTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LmsTransaction::factory()->count(50)->create();
    }
}
