<?php

namespace App\Service;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class LoginService
{
    public function login(array $credentials)
    {
        $currentDateTime = now();

        try {
            // Attempt to authenticate the user
            if (!$accessToken = auth('api')->attempt($credentials)) {
                Log::channel('warning')->warning("[$currentDateTime]: Authentication failed for credentials");
                return [
                    "message" => "Authentication failed",
                    "status" => "fail",
                ];
            }

            // Get the authenticated user
            $user = auth('api')->user();
            Log::channel('info')->info("[$currentDateTime]: User authenticated successfully, ID: " . $user->id);

            // Generate and store refresh token in Redis
            $refreshToken = Str::random(60); // Generate a random refresh token
            // Store refresh token and user data in Redis
            Redis::set('access_token', $accessToken);
            
            Redis::set('refresh_token', $refreshToken);
            Redis::set('user_id', $user->id);
            Redis::set('email', $user->email);
           
            // Return the access token, refresh token, and user data
            return [
                'token' => $accessToken,    
                'refresh_token' => $refreshToken,
                'user' => $user,
            ];
        } catch (\Exception $e) {
            Log::channel('error')->error("[$currentDateTime]: Login error: " . $e->getMessage());
            throw $e;
        }
    }
}
