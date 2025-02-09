<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Service\LoanViewDetailsService;

class LoanViewDetailsController extends Controller
{
    public function __construct(protected LoanViewDetailsService $loanViewDetailsService)
    {
        $this->loanViewDetailsService = $loanViewDetailsService;
    }

    public function loanViewDetails(Request $request)
    {
        try {
            $responseData = $this->loanViewDetailsService->loanViewDetailsProcess($request);
            Log::channel('info')->info("LoanViewDetailsController: " . json_encode($responseData));
            if (strtolower($responseData['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'Loan submitted successfully', 'data' => $responseData['data']], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'Failed to submit loan.'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
