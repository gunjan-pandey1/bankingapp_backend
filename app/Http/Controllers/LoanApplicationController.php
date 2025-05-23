<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Service\LoanApplicationService;
use Illuminate\Support\Facades\Session;

class LoanApplicationController extends Controller
{
    public function __construct(protected LoanApplicationService $loanApplicationService)
    {
        $this->loanApplicationService = $loanApplicationService;
    }

    public function getLoans()
    {
        try {
            Log::channel('info')->info("LoanApplicationController::getLoans");
            $responseData = $this->loanApplicationService->getUserLoans();
            Log::channel('info')->info("LoanApplicationController: " . json_encode($responseData));
            
            if ($responseData['status'] === 'success') {
                return response()->json([
                    'status' => true,
                    'message' => $responseData['message'],
                    'data' => [
                        'loans' => $responseData['data']['loans'],
                        'banks' => $responseData['data']['banks'],
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
                'message' => 'An error occurred while fetching loans and bank details',
                'data' => []
            ], 500);
        }
    }
}