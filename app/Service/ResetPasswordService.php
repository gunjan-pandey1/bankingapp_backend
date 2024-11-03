<?php

namespace App\Service;

use Exception;
use Carbon\Carbon;
use App\Repository\ResetPasswordRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ResetPasswordService
{
    protected $resetPasswordRepository;

    public function __construct(ResetPasswordRepository $resetPasswordRepository)
    {
        $this->resetPasswordRepository = $resetPasswordRepository;
    }

    public function resetPassword(object $objectParams)
    {
        try {
            $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
            $email = $objectParams->email;
            $token = $objectParams->token;
            $newPassword = $objectParams->password;

            // Validate token (you may need to modify this logic to match your token validation system)
            if (!$this->resetPasswordRepository->isValidToken($email, $token)) {
                Log::channel('error')->error(message: "[$currentDateTime]: Invalid or expired token: " . json_encode($email, $token));
                return ['message' => 'Invalid or expired token','status' => 'error', 'data' => []
                ];
            }

            // Update password
            $hashedPassword = Hash::make($newPassword);
            $this->resetPasswordRepository->updatePassword($email, $hashedPassword);
            Log::info('Password updated successfully for email: ' . $email);
            return [
                'message' => 'Password reset successfully',
                'status' => 'success',
                'data' => []
            ];
        } catch (Exception $e) {
            Log::error('Error during password reset: ' . $e->getMessage());
            return [
                'message' => 'An error occurred during password reset',
                'status' => 'error',
                'data' => []
            ];
        }
    }
}