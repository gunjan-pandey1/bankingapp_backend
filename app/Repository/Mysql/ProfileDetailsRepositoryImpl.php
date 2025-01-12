<?php

namespace App\Repository\Mysql;

use App\Models\Loan;
use App\Models\User;
use App\Models\CreditScore;
use App\Models\LmsLoan;
use Illuminate\Support\Facades\Log;
use App\Repository\ProfileDetailsRepository;

class ProfileDetailsRepositoryImpl implements ProfileDetailsRepository
{
    public function fetchProfileInformation()
    {
        try {
            $userId = auth()->id();
            Log::channel('info')->info("Fetching profile for user ID: $userId");

            return User::select('name', 'email')
                ->where('id', $userId)
                ->first();
        } catch (\Exception $exception) {
            Log::channel('critical')->critical("Error fetching profile information: " . $exception->getMessage());
            return null;
        }
    }

    public function fetchCreditScore()
    {
        try {
            $userId = auth()->id();
            Log::channel('info')->info("Fetching credit score for user ID: $userId");

            // return CreditScore::select('score', 'rating')
                // ->where('user_id', $userId)
                // ->first();
        } catch (\Exception $exception) {
            Log::channel('critical')->critical("Error fetching credit score: " . $exception->getMessage());
            return null;
        }
    }

    public function fetchLoanHistory()
    {
        try {
            $userId = auth()->id();
            Log::channel('info')->info("Fetching loan history for user ID: $userId");

            return LmsLoan::select('loan_type', 'amount', 'status')
                ->where('user_id', $userId)
                ->get();
        } catch (\Exception $exception) {
            Log::channel('critical')->critical("Error fetching loan history: " . $exception->getMessage());
            return null;
        }
    }
}
