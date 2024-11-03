<?php

namespace App\Service;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    public function login(array $credentials)
    {
        try {
            $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');

            // Log the credentials for debugging (excluding sensitive data like password)
            Log::info("[$currentDateTime]: Attempting login for email: " . $credentials['email']);

            // Attempt to authenticate the user
            if (Auth::attempt($credentials)) {
                $user = Auth::user(); // Get the authenticated user
                
                // Log successful authentication
                Log::info("[$currentDateTime]: User authenticated successfully, ID: " . $user->id);

                // Generate JWT token using the 'jwt' guard explicitly
                $token = auth('api')->login($user); // Use the 'api' guard that uses JWT
                
                // Log the token generation
                Log::info("[$currentDateTime]: JWT token generated for user ID: " . $user->id);

                return [
                    'token' => $token, // Return the token
                    'user' => $user,
                ];
            } else {
                // Log the failure to authenticate
                Log::warning("[$currentDateTime]: Login attempt failed for email: " . $credentials['email']);
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

    protected function generateToken(User $user): void
    {
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');

        // Log token generation for the user
        Log::info("[$currentDateTime]: Generating token for user ID: " . $user->id);

        // Log the user in without returning a value
        auth()->login($user);

        // Log successful login
        Log::info("[$currentDateTime]: User logged in: " . $user->name);
    }
}
