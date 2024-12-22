<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanApplicationRequest;
use App\Service\LoanApplicationService;
use Illuminate\Support\Facades\Log;

class LoanApplicationController extends Controller
{
    public function __construct(protected LoanApplicationService $loanApplicationService)
    {
    }

    public function loanApplicationProcess(LoanApplicationRequest $loanApplicationRequest)
    {
        try {
            Log::channel('info')->info('Loan application attempt', ['data' => $loanApplicationRequest->safe()->all()]);
            
            $responseData = $this->loanApplicationService->getActiveLoans();
            
            if ($responseData['status'] === 'success') {
                return response()->json([
                    'message' => 'Loan application processed successfully',
                    'data' => $responseData['data']
                ], 201);
            }

            $statusCode = $responseData['message'] === 'Application failed' ? 422 : 400;
            
            return response()->json([
                'message' => $responseData['message'],
                'errors' => ['application' => [$responseData['message']]]
            ], $statusCode);

        } catch (\Exception $exception) {
            Log::channel('error')->error('Loan application failed', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Loan application failed',
                'errors' => ['server' => ['An unexpected error occurred']]
            ], 500);
        }
    }
}