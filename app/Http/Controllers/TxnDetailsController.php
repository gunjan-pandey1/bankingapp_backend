<?php

namespace App\Http\Controllers;

use App\Service\TxnDetailsService;
use App\Http\Requests\TxnDetailsRequest;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class TxnDetailsController extends Controller
{
    public function __construct(protected TxnDetailsService $txnDetailsService)
    {
        $this->txnDetailsService = $txnDetailsService;
    }

    public function txnDetailsProcess(TxnDetailsRequest $txnDetailsRequest)
    {
        try {
            // $responseData = $this->txnDetailsService->txnDetailsProcess($txnDetailsRequest->all());
            $userId = Auth::id();
            $transactions = $this->txnDetailsService->getUserTransactions($userId);

            if (strtolower($responseData['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'Transaction details retrieved successfully', 'data' => $responseData['data']], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'Failed to retrieve transaction details'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
