<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;  // Changed from Illuminate\Http\Client\Request
use Illuminate\Routing\Controller;
use App\Service\BankDetailsService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\BankDetailsRequest;

class BankDetailsController extends Controller
{
    public function __construct(protected BankDetailsService $bankDetailsService)
    {
        $this->bankDetailsService = $bankDetailsService;
    }

    public function bankDetailsProcess(Request $request)  // Changed parameter name to $request
    {
        Log::channel('info')->info('bankDetailsProcess: Bank details attempt', ['data' => $request->all()]);
        
        try {
            $responseData = $this->bankDetailsService->saveBankDetails($request);
            
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