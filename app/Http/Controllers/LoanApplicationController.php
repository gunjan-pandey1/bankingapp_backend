<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\LoanApplicationService;
use App\Http\Requests\LoanApplicationRequest;

class LoanApplicationController extends Controller
{
    public function __construct(protected LoanApplicationService $loanApplicationService)
    {
        $this->loanApplicationService = $loanApplicationService;
    }

    public function loanApplicationProcess(LoanApplicationRequest $loanApplicationRequest)
    {
        try {
            $responseData = $this->loanApplicationService->loanApplication($loanApplicationRequest);
            if (strtolower($responseData['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'Loan application processed successfully', "data" => $responseData["data"]], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'Loan application failed'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
