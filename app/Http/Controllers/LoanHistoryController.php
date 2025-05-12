<?php

namespace App\Http\Controllers;

use App\Service\LoanHistoryService;
use Illuminate\Support\Facades\Log;

class LoanHistoryController extends Controller
{
    public function __construct(protected LoanHistoryService $loanHistoryService)
    {
        $this->loanHistoryService = $loanHistoryService;
    }

    public function getUserLoans()
    {
        try {
            Log::channel('info')->info("LoanHistoryController::getLoans");
            $responseData = $this->loanHistoryService->getUserspecificLoans();
            Log::channel('info')->info("LoanHistoryController: " . json_encode($responseData));
            
            if ($responseData['status'] === 'success') {
                return response()->json([
                    'status' => true,
                    'message' => $responseData['message'],
                    'data' => [
                        'loans' => $responseData['data']['loans'],
                    ],
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $responseData['message'],
                    'data' => [],
                ], 500);
            }
        } catch (\Exception $exception) {
            Log::error("LoanApplicationController error: " . $exception->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching loans.       ',
                'data' => []
            ], 500);
        }
    }
}