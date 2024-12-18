<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Http\Middleware\RefreshToken;
use App\Models\User; // Ensure the User model is imported

class AuthenticateWithRefreshToken
{
    public function handle($request, Closure $next)
    {
        $currentDateTime = now();

        try {
            // Check if the access token is valid
            if (auth('api')->check()) {
                return $next($request); 
            }

            // Access token is invalid, check for a refresh token
            $refreshToken = $request->header('Refresh-Token');

            if (!$refreshToken) {
                Log::channel('warning')->warning("[$currentDateTime]: Missing refresh token");
                return [
                    "message" => "Unauthorized: Missing refresh token",
                    "status" => "fail",
                ];
            }

            // $refreshTokenKey = "refresh_token:$refreshToken";
            // $userId = Redis::get($refreshTokenKey); 

            // Check for the user ID in the session instead of Redis
            $userId = session()->get("refresh_token:$refreshToken"); 

            if (!$userId) {
                Log::channel('warning')->warning("[$currentDateTime]: Invalid or expired refresh token");
                return [
                    "message" => "Unauthorized: Invalid or expired refresh token",
                    "status" => "fail",
                ];
            }

            // Retrieve the user using the User model
            $user = User::find($userId);

            if (!$user) {
                Log::channel('warning')->warning("[$currentDateTime]: User not found for ID: $userId");
                return [
                    "message" => "Unauthorized: User not found",
                    "status" => "fail",
                ];
            }

            // Generate a new access token using JWTAuth
            $newAccessToken = JWTAuth::fromUser($user);
            $newRefreshToken = \Illuminate\Support\Str::random(60);
            // Store the new refresh token in the session
            session()->put("refresh_token:$newRefreshToken", $userId);

            // Update Redis with the new refresh token
            // Redis::del($refreshTokenKey); // Remove the old refresh token
            // Redis::setex($newRefreshTokenKey, 604800, $userId); // Store the new refresh token for 7 days

            // Add the new tokens to the response headers
            $response = $next($request);
            return $response
                ->header('Authorization', "Bearer $newAccessToken")
                ->header('Refresh-Token', $newRefreshToken);
        } catch (\Exception $e) {
            Log::channel('error')->error("[$currentDateTime]: Middleware authentication error: " . $e->getMessage());
            return [
                "message" => "Unauthorized: Server error",
                "status" => "fail",
            ];
        }
    }
}
