<?php

namespace App\Repository\Mysql;

use App\Models\User;
use App\Repository\ResetPasswordRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResetPasswordRepositoryImpl implements ResetPasswordRepository
{
    public function isValidToken(string $email, string $token): bool
    {
        // Assuming a tokens table exists for storing the reset tokens
        $tokenData = DB::table('user')->where('email', $email)->where('token', $token)->first();

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
            return User::where('email', $email)->update(['password' => $newPassword]);
        } catch (\Exception $e) {
            Log::error('Error updating password for email ' . $email . ': ' . $e->getMessage());
            return false;
        }
    }
}
