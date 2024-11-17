<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\LoginService;

class LoginController extends Controller
{
    public function __construct(protected LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function loginProcess(LoginRequest $loginRequest)
    {
        try {
            $responseData = $this->loginService->login($loginRequest);
            if (strtolower($responseData['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'Login successful', "data" => $responseData["data"]], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'Login failed'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
