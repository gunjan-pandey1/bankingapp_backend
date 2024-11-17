<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\LoanViewDetailsService;
use App\Http\Requests\LoanViewDetailsRequest;

class LoanViewDetailsController extends Controller
{
    public function __construct(protected LoanViewDetailsService $loanViewDetailsService)
    {
        $this->loanViewDetailsService = $loanViewDetailsService;
    }

    public function loanViewDetailsProcess(LoanViewDetailsRequest $loanViewDetailsRequest)
    {
        try {
            $responseData = $this->loanViewDetailsService->loanViewDetails($loanViewDetailsRequest);
            if (strtolower($responseData['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'Loan view details retrieved successfully', "data" => $responseData["data"]], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'Failed to retrieve loan view details'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
