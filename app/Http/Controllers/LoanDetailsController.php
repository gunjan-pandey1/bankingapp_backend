<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\LoanDetailsService;
use App\Http\Requests\LoanDetailsRequest;

class LoanDetailsController extends Controller
{
    public function __construct(protected LoanDetailsService $loanDetailsService)
    {
        $this->loanDetailsService = $loanDetailsService;
    }

    public function loanDetailsProcess(LoanDetailsRequest $loanDetailsRequest)
    {
        try {
            $responseData = $this->loanDetailsService->loanDetails($loanDetailsRequest);
            if (strtolower($responseData['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'Loan details retrieved successfully', "data" => $responseData["data"]], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'Failed to retrieve loan details'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
