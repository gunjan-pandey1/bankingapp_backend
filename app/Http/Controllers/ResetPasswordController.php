<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Service\ResetPasswordService;

class ResetPasswordController extends Controller
{
    public function __construct(protected ResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    public function resetPasswordProcess(ResetPasswordRequest $resetPasswordRequest)
    {
        try {
            $responseData = $this->resetPasswordService->resetPassword($resetPasswordRequest);
            if (strtolower($responseData['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'Password reset successful', 'data' => $responseData['data']], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'Password reset failed'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
