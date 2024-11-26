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
        Log::channel('info')->info("LoginController::loginProcess", $loginRequest->toArray());
        try {
            $credentials = $loginRequest->only('email', 'password');
            $responseData = $this->loginService->login($credentials);
            if (strtolower($responseData['status']) == 'success') {
                Log::channel('info')->info("LoginController", $responseData);
                
                return response()->json(['success' => true, 'message' => 'Login successful', 'data' => $responseData['data']], 200);
            } else {
                Log::channel('error')->error("LoginController", $responseData);
                return response()->json(['success' => false,  'message' => 'Login failed'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);

            
        }
    }
}
