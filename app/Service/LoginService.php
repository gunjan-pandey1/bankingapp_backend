<?php

namespace App\Service;

use Illuminate\Support\Facades\Log;

class LoginService
{
    public function login(array $credentials)
    {
        $currentDateTime = now();
        try {
            if (!$token = auth('api')->attempt($credentials)) {
                Log::channel('warning')->warning("[$currentDateTime]: Login attempt failed for email: " . $credentials['email']);
                return [
                    "message" => "Invalid credentials",
                    "status" => "fail",
                ];
            }
            $user = auth('api')->user();
            Log::channel('info')->info("[$currentDateTime]: User authenticated successfully, ID: " . $user->id);

            return [
                'token' => $token,
                'user' => $user,
            ];
            
        } catch (\Exception $e) {
            Log::channel('error')->error("[$currentDateTime]: Login error: " . $e->getMessage());
            throw $e;
        }
    }
}
