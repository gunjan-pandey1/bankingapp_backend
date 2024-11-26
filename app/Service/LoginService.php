<?php

namespace App\Service;

use Carbon\Carbon;
use App\Models\LmsLoan;
use App\Models\LmsUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    public function login(array $credentials)
    {
        try {
            $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
    
            // Find the user by email
            $user = LmsUser::where('email', $credentials['email'])->first();
    
            // Check if user exists and verify the password
            if ($user && Hash::check($credentials['password'], $user->password)) {
                // Log successful authentication
                Log::channel('success')->info("[$currentDateTime]: User authenticated successfully, ID: " . $user->id);
                return [
                    "message" => "Login Successful",
                    "status" => "success", // or "fail"
                    "data" => $user,
                ];
            } else {
                // Log the failure to authenticate
                Log::channel('error')->error("[$currentDateTime]: Login attempt failed for email: " . $credentials['email']);
                return [
                    "message" => "Invalid credentials",
                    "status" => "fail",
                    "data" => []
                ];
            }
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::channel('error')->error("[$currentDateTime] Error during login: " . $e->getMessage());
            return [
                "message" => "An error occurred during login",
                "status" => "error",
                "data" => []
            ];
        }
    }
}
