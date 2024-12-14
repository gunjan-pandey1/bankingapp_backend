<?php

namespace App\Http\Controllers;

use App\Service\LoginService;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function __construct(protected LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function loginProcess(LoginRequest $loginRequest)
    {
        try {
            Log::channel('info')->info("LoginController::loginProcess", $loginRequest->toArray());
            $credentials = $loginRequest->only('email', 'password');
            $responseData = $this->loginService->login($credentials);
            if (!$responseData || isset($responseData['status']) && $responseData['status'] === 'fail') {
                return response()->json([
                    'success' => false,
                    'message' => $responseData['message'] ?? 'Login failed'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'token' => $responseData['token'],
                    'refresh_token' => $responseData['refresh_token'],
                    'user' => $responseData['user'],
                ]
            ], 200);
        } catch (\Exception $exception) {
            Log::channel('error')->error("LoginController error: " . $exception->getMessage());
            throw $exception;
        }
    }
}
