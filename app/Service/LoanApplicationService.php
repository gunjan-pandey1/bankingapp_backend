<?php

namespace App\Service;

use Carbon\Carbon;
use App\Models\LmsLoan;
use App\Constants\CommonConstant;
use App\Repository\LoanRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class LoanApplicationService
{
    public function __construct(protected LoanRepository $loanRepository)
    {}
    public function getUserLoans()
    {
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        try {
            $userId = Redis::get('user_id');
            Log::channel('info')->info("LoanApplicationService: User ID from session: " . $userId);
    
            // Get all loans
            $loansdbResponse = $this->loanRepository->getAllLoans($userId);
            Log::channel('info')->info("LoanApplicationService: Loans data: " . json_encode($loansdbResponse));         
    
            // Transform loans to match frontend format
            $transformedLoans = $loansdbResponse->map(function ($loan) {
                return [
                    'type' => $loan->loan_type,
                    'amount' => number_format($loan->amount, 0, '.', ''),
                    'interest' => number_format($loan->interest_rate, 1),
                    'term' => $loan->duration_month,
                    'status' => $loan->status,
                    'nextPayment' => $loan->next_payment_date ?? 'N/A'
                ];
            });
    
            // Get all banks
            $banks = $this->loanRepository->getAllBanks($userId);
            Log::channel('info')->info("LoanApplicationService: Banks data: " . json_encode($banks));
            
            if (!$banks) {
                return [
                    'status' => 'error',
                    'message' => 'Bank details not found',
                    'data' => [
                        'banks' => []
                    ]
                ];
            }
    
            $transformedBanks = $banks->map(function ($bank) {
                return [
                    'bankName' => $bank->bank_name,
                ];
            });
    
            Log::channel('info')->info("LoanApplicationService: Transformed banks data: " . json_encode($transformedBanks));
            
            return [
                "message" => "Data fetched successfully",
                "status" => "success",
                "data" => [
                    'banks' => $transformedBanks
                ]
            ];
        } catch (\Throwable $th) {
            Log::channel('critical')->critical("LoanApplicationService error: " . $th->getMessage());
            return [
                "status" => "error",
                "message" => "Failed to fetch user loans and bank data.",
                "data" => []
            ];
        }
    }
}
