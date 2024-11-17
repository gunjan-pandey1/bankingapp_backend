<?php

namespace Database\Seeders;

use App\Models\LmsUser;
use Illuminate\Database\Seeder;

class LmsUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LmsUser::factory()->count(50)->create();
    }
}
