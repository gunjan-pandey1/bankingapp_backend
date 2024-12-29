<?php

namespace App\Service;

use Carbon\Carbon;
use App\Models\LmsLoan;
use App\Constants\CommonConstant;
use App\Repository\LoanRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class LoanApplicationService
{
    public function __construct(protected LoanRepository $loanRepository)
    {}
    public function getUserLoans($userId)
    {
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        try {
            $userId = Redis::get("user_id:" . session()->get('user_id'));
            // $userId = auth()->id();
            Log::channel('info')->info("LoanApplicationService: User ID from session: " . json_encode($userId));
            $loansdbResponse = $this->loanRepository->getAllLoans($userId);
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

            return [
                "message" => "Data fetched successfully",
                "status" => "success",
                "data" => $transformedLoans
            ];
        } catch (\Throwable $th) {
            Log::channel('critical')->critical("LoanApplicationService error: " . $th->getMessage());
            return [
                "status" => "error",
                "message" => "Failed to fetch user loans data.",
                "data" => []
            ];
        }
    }
}
