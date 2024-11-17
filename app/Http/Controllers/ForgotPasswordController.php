<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Service\ForgotPasswordService;

class ForgotPasswordController extends Controller
{
    public function __construct(protected ForgotPasswordService $forgotPasswordService)
    {
        $this->forgotPasswordService = $forgotPasswordService;
    }

    public function forgotPasswordProcess(ForgotPasswordRequest $forgotPasswordRequest)
    {
        try {
            $responseData = $this->forgotPasswordService->forgotPassword($forgotPasswordRequest);
            if (strtolower($responseData['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'Password reset link sent successfully', 'data' => $responseData['data']], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'Failed to send password reset link'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
