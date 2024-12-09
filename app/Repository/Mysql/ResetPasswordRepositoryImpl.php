<?php

namespace App\Repository\Mysql;

use Carbon\Carbon;
use App\Models\LmsUser;
use Illuminate\Support\Facades\Log;
use App\Repository\ResetPasswordRepository;

class ResetPasswordRepositoryImpl implements ResetPasswordRepository
{
    public function isValidToken(string $email, string $token): bool
    {
        // Assuming a tokens table exists for storing the reset tokens
        $tokenData = LmsUser::where('email', $email)->where('token', $token)->first(); // Replace 'tokens' with your actual table nameUser::where('email', $email)->where('token', $token)->first();

        if ($tokenData) {
            $tokenCreationDate = Carbon::parse($tokenData->created_at);
            $isExpired = $tokenCreationDate->diffInMinutes(Carbon::now()) > 60; // 60-minute expiry time
            return !$isExpired;
        }

        return false;
    }

    public function updatePassword(string $email, string $newPassword): bool
    {
        try {
            return LmsUser::where('email', $email)->update(['password' => $newPassword]);
        } catch (\Exception $e) {
            Log::channel('error')->error('Error updating password for email ' . $email . ': ' . $e->getMessage());
            return false;
        }
    }
}
