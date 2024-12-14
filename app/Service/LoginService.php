<?php

namespace App\Service;

use Tymon\JWTAuth\JWT;
use Illuminate\Support\Facades\Log;

class LoginService
{
    public function login(array $credentials)
    {
        $currentDateTime = now();

        try {
            // Get the authenticated user
            $user = auth('api')->user();

            if (!$user) {
                Log::channel('warning')->warning("[$currentDateTime]: User not authenticated");
                return [
                    "message" => "Authentication failed",
                    "status" => "fail",
                ];
            }
            Log::channel('info')->info("[$currentDateTime]: User authenticated successfully, ID: " . $user->id);

            // Generate Access Token (JWT)
            $accessPayload = [
                'iss' => config('app.url'), // The issuer
                'sub' => $user->id,        // Subject - User ID
                'iat' => time(),           // Issued at
                'exp' => time() + (60 * 60) // 1 hour expiration
            ];
            $accessToken = JWT::encode($accessPayload, env('JWT_SECRET'), 'HS256');

            // Generate Refresh Token
            $refreshPayload = [
                'iss' => config('app.url'), // The issuer
                'sub' => $user->id,        // Subject - User ID
                'iat' => time(),           // Issued at
                'exp' => time() + (7 * 24 * 60 * 60) // 7 days expiration
            ];
            $refreshToken = JWT::encode($refreshPayload, env('JWT_SECRET'), 'HS256');

            // Return both tokens and user data
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
