<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Facades\JWTAuth;
use Closure;
use Tymon\JWTAuth\Exceptions\TokenExpiredException; 
use Exception; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JwtAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        try {
            $decoded = JWTAuth::setToken(substr($token, 7))->getPayload(); // Use JWTAuth to decode
            $request->attributes->add(['user' => $decoded]);
        } catch (TokenExpiredException $e) {
            Log::channel('error')->error("[2024-12-10T23:01:43+05:30]: Token expired: " . $e->getMessage());
            return response()->json(['message' => 'Token expired'], 401);
        } catch (Exception $e) {
            Log::channel('error')->error("[2024-12-10T23:01:43+05:30]: Token invalid: " . $e->getMessage());
            return response()->json(['message' => 'Token invalid'], 401);
        }
        return $next($request);
    }
}
