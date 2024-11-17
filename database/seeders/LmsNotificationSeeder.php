<?php

namespace Database\Seeders;

use App\Models\LmsNotification;
use Illuminate\Database\Seeder;

class LmsNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LmsNotification::factory()->count(50)->create();
    }
}
