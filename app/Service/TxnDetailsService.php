<?php

namespace App\Service;

use Carbon\Carbon;
use App\Constants\CommonConstant;
use Illuminate\Support\Facades\Log;
use App\Repository\TxnDetailsRepository;

class TxnDetailsService
{
    protected $txnDetailsRepository;

    public function __construct(TxnDetailsRepository $txnDetailsRepository)
    {
        $this->txnDetailsRepository = $txnDetailsRepository;
    }

    public function getUserTransactions($userId)
    {
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        try {
            Log::channel('info')->info("TxnDetailsService: User ID " . $userId);

            $transactionsResponse = $this->txnDetailsRepository->getUserTransactions($userId);
            Log::channel('info')->info("TxnDetailsService: Transactions data: " . json_encode($transactionsResponse));

            $getData = $transactionsResponse->map(function ($transaction) {
                return [
                    'user_id' => $transaction->user_id,
                    'loan_id' => $transaction->loan_id,
                    'transaction_type' => $transaction->transaction_type,
                    'transaction_amount' => $transaction->transaction_amount,
                    'description' => $transaction->description,
                    'txnDate' => $transaction->txnDate
                ];
            });
            Log::channel('info')->info("TxnDetailsService: Transformed transaction data: " . json_encode($getData));

            return ['status' => 'success', 'message' => 'Transactions retrieved successfully', "data" => $getData];

        }
        catch (\Exception $exception) {
            Log::channel('error')->error("[$currentDateTime] User ID: $userId - " . $exception->getMessage());
            return ['status' => CommonConstant::ERROR, 'message' => $exception->getMessage(), "data" => []];
        }
    }
}
