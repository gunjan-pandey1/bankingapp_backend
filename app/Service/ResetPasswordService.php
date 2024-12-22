<?php

namespace App\Service;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Repository\ResetPasswordRepository;
use App\Jobs\SendResetPasswordEmail;
use Illuminate\Support\Facades\Password;

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
            $token = $objectParams->token; // This token is already provided
            $newPassword = $objectParams->password;
    
            // Validate token
            if (!$this->resetPasswordRepository->isValidToken($email, $token)) {
                Log::channel('error')->error(message: "[$currentDateTime]: Invalid or expired token: " . json_encode($email, $token));
                return ['message' => 'Invalid or expired token', 'status' => 'error', 'data' => []];
            }
    
            // Update password
            $hashedPassword = Hash::make($newPassword);
            $this->resetPasswordRepository->updatePassword($email, $hashedPassword);
            
            // Dispatch the email sending job
            SendResetPasswordEmail::dispatch($email, $token);
    
            Log::channel('info')->info('Password updated successfully for email: ' . $email);
            return [
                'message' => 'Password reset successfully',
                'status' => 'success',
                'data' => []
            ];
        } catch (Exception $e) {
            Log::channel('error')->error('Error during password reset: ' . $e->getMessage());
            return [
                'message' => 'An error occurred during password reset',
                'status' => 'error',
                'data' => []
            ];
        }
    }
}
