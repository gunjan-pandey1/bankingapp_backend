<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\EmiRepaymentService;
use App\Http\Requests\EmiRepaymentRequest;

class EmiRepaymentController extends Controller
{
    public function __construct(protected EmiRepaymentService $emiRepaymentService)
    {
        $this->emiRepaymentService = $emiRepaymentService;
    }

    public function emiRepaymentProcess(EmiRepaymentRequest $emiRepaymentRequest)
    {
        try {
            $responseData = $this->emiRepaymentService->emiRepayment($emiRepaymentRequest);
            if (strtolower($responseData['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'EMI repaid successfully', "data" => $responseData["data"]], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'EMI repayment failed'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
