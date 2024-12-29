<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Service\LoanApplicationService;

class LoanApplicationController extends Controller
{
    public function __construct(protected LoanApplicationService $loanApplicationService)
    {
        $this->loanApplicationService = $loanApplicationService;
    }

    public function getLoans()
    {

        $userId = Redis::get("user_id:$user->id");
        try {
            Log::channel('info')->info("LoanApplicationController::getLoans");
           
            // $userId = auth()->id();
            Log::channel('info')->info("LoanApplicationController: User ID from redis: " . json_encode($userId));
            $responseData = $this->loanApplicationService->getUserLoans($userId);
            Log::channel('info')->info("LoanApplicationController: " . json_encode($responseData));
            if ($responseData['status'] === 'success') {
                return response()->json([
                    'status' => true,
                    'message' => $responseData['message'],
                    'data' => $responseData['data'],
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
                'message' => 'An error occurred while fetching loans',
                'data' => []
            ], 500);
        }
    }
}