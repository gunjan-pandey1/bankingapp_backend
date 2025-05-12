<?php

namespace App\Service;

use Carbon\Carbon;
use App\Models\LmsLoan;
use App\Constants\CommonConstant;
use App\Repository\LoanHistoryRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class LoanHistoryService
{
    public function __construct(protected LoanHistoryRepository $loanHistoryRepository)
    {}
    public function getUserspecificLoans()
    {
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        try {
            $userId = Redis::get('user_id');
            Log::channel('info')->info("LoanHistoryService: User ID from session: " . $userId);
    
            // Get all loans
            $loansdbResponse = $this->loanHistoryRepository->getAllUserLoans($userId);
            Log::channel('info')->info("LoanHistoryService: Loans data: " . json_encode($loansdbResponse));         
    
            // Transform loans to match frontend format
            $transformedLoans = $loansdbResponse->map(function ($loan) {
                return [
                    'loan_id' => $loan->id,
                    'type' => $loan->loan_type,
                    'amount' => number_format($loan->amount, 0, '.', ''),
                    'term' => $loan->duration_month,
                    'status' => $loan->status,
                ];
            });
            Log::channel('info')->info("LoanHistoryService: Transformed loan data: " . json_encode($transformedLoans));
            
            return [
                "message" => "Data fetched successfully",
                "status" => "success",
                "data" => [
                    'loans' => $transformedLoans,
                ]
            ];
        } catch (\Throwable $th) {
            Log::channel('critical')->critical("LoanHistoryService error: " . $th->getMessage());
            return [
                "status" => "error",
                "message" => "Failed to fetch user loans and bank data in loan history.",
                "data" => []
            ];
        }
    }
}
