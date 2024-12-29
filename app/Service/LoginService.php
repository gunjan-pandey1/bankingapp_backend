<?php

namespace App\Service;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User; // Ensure your User model is imported

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
            $refreshTokenKey = "refresh_token:$refreshToken";
            Redis::setex($refreshTokenKey, 604800, $user->id); // Store in Redis for 7 days (604800 seconds)    
            Redis::setex("user_id:$user->id", 604800, $user->id);
            Redis::setex("email:$user->email", 604800, $user->email);
       
            Log::channel('info')->info('Session user_id set: ' . session()->get('user_id'));

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
