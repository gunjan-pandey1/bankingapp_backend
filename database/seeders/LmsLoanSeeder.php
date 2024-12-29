<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LmsLoan;

class LmsLoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add 5 sample entries to the lms_loan table with created_timestamp and updated_timestamp
        LmsLoan::insert([
            [
                
                'loan_type' => 'Personal',
                'amount' => 5000,
                'interest_rate' => 5.5,
                'duration_month' => 24,
                'status' => 1, // Approved
                'is_show_flag' => true,
                'created_date' => now(),
                'updated_date' => now(),
                'created_timestamp' => now()->format('Y-m-d H:i:s'),
                'updated_timestamp' => now()->format('Y-m-d H:i:s'),
            ],
            [
                
                'loan_type' => 'Home',
                'amount' => 15000,
                'interest_rate' => 3.2,
                'duration_month' => 120,
                'status' => 0, // Pending
                'is_show_flag' => true,
                'created_date' => now(),
                'updated_date' => now(),
                'created_timestamp' => now()->format('Y-m-d H:i:s'),
                'updated_timestamp' => now()->format('Y-m-d H:i:s'),
            ],
            [
                
                'loan_type' => 'Car',
                'amount' => 10000,
                'interest_rate' => 4.5,
                'duration_month' => 60,
                'status' => 2, // Rejected
                'is_show_flag' => false,
                'created_date' => now(),
                'updated_date' => now(),
                'created_timestamp' => now()->format('Y-m-d H:i:s'),
                'updated_timestamp' => now()->format('Y-m-d H:i:s'),
            ],
            [
                
                'loan_type' => 'Business',
                'amount' => 25000,
                'interest_rate' => 6.0,
                'duration_month' => 48,
                'status' => 1, // Approved
                'is_show_flag' => true,
                'created_date' => now(),
                'updated_date' => now(),
                'created_timestamp' => now()->format('Y-m-d H:i:s'),
                'updated_timestamp' => now()->format('Y-m-d H:i:s'),
            ],
            [
                
                'loan_type' => 'Education',
                'amount' => 7000,
                'interest_rate' => 4.0,
                'duration_month' => 36,
                'status' => 0, // Pending
                'is_show_flag' => true,
                'created_date' => now(),
                'updated_date' => now(),
                'created_timestamp' => now()->format('Y-m-d H:i:s'),
                'updated_timestamp' => now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
