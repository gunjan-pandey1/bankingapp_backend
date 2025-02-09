<?php

namespace App\Http\Controllers;

use App\Service\TxnDetailsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\TxnDetailsRequest;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class TxnDetailsController extends Controller
{
    public function __construct(protected TxnDetailsService $txnDetailsService)
    {
        $this->txnDetailsService = $txnDetailsService;
    }

    public function txnDetailsProcess()
    {
        Log::channel('info')->info("TxnDetailsController: User ID " . Redis::get('user_id'));
        try {
            $userId = Redis::get('user_id');
            Log::channel('info')->info("TxnDetailsController: User ID " . $userId);
            $transactionsResponse = $this->txnDetailsService->getUserTransactions($userId);
            Log::channel('info')->info("TxnDetailsController: Transactions response " . json_encode($transactionsResponse));
            if (strtolower($transactionsResponse['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'Transaction details retrieved successfully', 'data' => $transactionsResponse['data']], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'Failed to retrieve transaction details'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
