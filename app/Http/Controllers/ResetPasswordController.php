<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Service\ResetPasswordService;
use App\Http\Requests\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    protected $resetPasswordService;

    public function __construct(ResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    public function reset(ResetPasswordRequest $resetPasswordRequest)
    {
        try {
            Log::info($resetPasswordRequest->all());
            $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
            $responseData = $this->resetPasswordService->resetpassword($resetPasswordRequest);
            Log::channel('error')->error("[$currentDateTime] reset_password_error:", $responseData);


            if ($responseData['status'] == 'success') {
                return response()->json(['message' => 'Password reset successfully','data' => $responseData['data']], 200);
            } else {
                return response()->json(['message' => $responseData['message'],'errors' => $responseData['data']], 422);
            }
        } catch (\Exception $e) {
            Log::error('Error occurred during password reset: ' . $e->getMessage());
            return response()->json([ 'message' => 'An error occurred while resetting password', 'errors' => ['internal_error' => 'Error occurred while resetting password']], 500);
        }
    }
}
