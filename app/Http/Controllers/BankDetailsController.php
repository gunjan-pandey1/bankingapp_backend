<?php

namespace App\Http\Controllers;

use App\Service\BankDetailsService;

class BankDetailsController extends Controller
{
    public function __construct(protected BankDetailsService $bankDetailsService)
    {
        $this->bankDetailsService = $bankDetailsService;
    }

    public function bankDetailsProcess(BankDetailsRequest $bankDetailsRequest)
    {
        try {
            $responseData = $this->bankDetailsService->bankDetails($bankDetailsRequest);
            if (strtolower($responseData['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'Bank details added successfully', 'data' => $responseData['data']], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'Bank details not added'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
