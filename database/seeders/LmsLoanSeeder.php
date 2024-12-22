<?php

namespace Database\Seeders;

use App\Models\LmsLoan;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LmsLoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $loanTypes = ['Personal Loan', 'Home Loan', 'Car Loan', 'Business Loan'];
        $statuses = [
            0 => 'Pending',
            1 => 'Active',
            2 => 'Closed'
        ];
        
        // Generate 20 sample loans
        for ($i = 0; $i < 20; $i++) {
            $amount = rand(5000, 50000);
            $type = $loanTypes[array_rand($loanTypes)];
            $term = rand(12, 60);
            
            // Interest rate based on loan type
            $interest = match($type) {
                'Personal Loan' => rand(850, 1500) / 100, // 8.5% - 15%
                'Home Loan' => rand(650, 900) / 100,     // 6.5% - 9%
                'Car Loan' => rand(700, 1200) / 100,     // 7% - 12%
                'Business Loan' => rand(1000, 1800) / 100, // 10% - 18%
            };
            
            LmsLoan::create([
                'user_id' => rand(1, 10), // Assuming you have 10 users
                'loan_type' => $type,
                'amount' => $amount,
                'interest_rate' => $interest,
                'duration_month' => $term,
                'status' => array_rand($statuses),
                'next_payment' => Carbon::now()->addDays(rand(1, 30)),
                'created_date' => Carbon::now()->subDays(rand(1, 365)),
                'updated_date' => Carbon::now(),
                'created_timestamp' => Carbon::now(),
                'updated_timestamp' => Carbon::now(),
                'is_show_flag' => 1,
            ]);
        }
    }
}