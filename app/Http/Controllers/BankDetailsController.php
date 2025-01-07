<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Service\BankDetailsService;
use App\Http\Requests\BankDetailsRequest;
use Illuminate\Support\Facades\Log;

class BankDetailsController extends Controller
{
    public function __construct(protected BankDetailsService $bankDetailsService)
    {
        $this->bankDetailsService = $bankDetailsService;
    }

    public function bankDetailsProcess(BankDetailsRequest $bankDetailsRequest)
    {
        Log::channel('info')->info('Bank details attempt', ['data' => $bankDetailsRequest->safe()->all()]);
        try {
            $responseData = $this->bankDetailsService->saveBankDetails($bankDetailsRequest);
            if ($responseData['status'] === 'success') {
                return response()->json([
                    'message' => 'Bank details submitted successfully',
                    'data' => $responseData['data']
                ], 201);
            }
            $statusCode = $responseData['message'] === 'Bank details already exist for user' ? 422 : 400;

            return response()->json([
                'message' => $responseData['message'],
                'errors' => ['email' => [$responseData['message']]]
            ], $statusCode);

        } catch (\Exception $exception) {
            Log::channel('error')->error('Bank details failed', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
