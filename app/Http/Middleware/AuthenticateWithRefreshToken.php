<?php
namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AuthenticateWithRefreshToken
{
    public function handle($request, Closure $next)
    {
        $currentDateTime = now();

        try {

            Log::channel('info')->info("[$currentDateTime]: Middleware authentication started", $request->all());
            // Check if the access token is valid
            if (auth('api')->check()) {
                return $next($request);
            }

            // Access token is invalid, check for a refresh token
            $refreshToken = $request->header('Refresh-Token');

            if (!$refreshToken) {
                Log::channel('warning')->warning("[$currentDateTime]: Missing refresh token");
                return response()->json([
                    "message" => "Unauthorized: Missing refresh token",
                    "status" => "fail",
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Check for the user ID in the session instead of Redis
            $userId = session()->get("refresh_token:$refreshToken");

            if (!$userId) {
                Log::channel('warning')->warning("[$currentDateTime]: Invalid or expired refresh token");
                return response()->json([
                    "message" => "Unauthorized: Invalid or expired refresh token",
                    "status" => "fail",
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Retrieve the user using the User model
            $user = User::find($userId);

            if (!$user) {
                Log::channel('warning')->warning("[$currentDateTime]: User not found for ID: $userId");
                return response()->json([
                    "message" => "Unauthorized: User not found",
                    "status" => "fail",
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Generate a new access token using JWTAuth
            $newAccessToken = JWTAuth::fromUser($user);
            $newRefreshToken = \Illuminate\Support\Str::random(60);
            // Store the new refresh token in the session
            session()->put("refresh_token:$newRefreshToken", $userId);

            // Add the new tokens to the response headers
            $response = $next($request);
            return $response
                ->header('Authorization', "Bearer $newAccessToken")
                ->header('Refresh-Token', $newRefreshToken);
        } catch (\Exception $e) {
            Log::channel('error')->error("[$currentDateTime]: Middleware authentication error: " . $e->getMessage());
            return response()->json([
                "message" => "Unauthorized: Server error",
                "status" => "fail",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
