<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Service\LoginService;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function __construct(protected LoginService $loginService)
    {}

    public function login(LoginRequest $request)
    {
        try {
            Log::info($request->all());
            $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
            $credentials = $request->only('email', 'password');
            $loginResponse = $this->loginService->login($credentials);
            Log::channel('error')->error("[$currentDateTime] login_error:", $loginResponse);


            if ($loginResponse && $loginResponse['token']) {
                return response()->json(['token' => $loginResponse['token'], 'user' => $loginResponse['user']], 200);
            }
        }
        catch (\Exception $e) {
            Log::channel('error')->error("[$currentDateTime] Error occurred: ".$e->getMessage());
            return response()->json(['message' => 'Invalid credentials'], 401);
        }


    }
}
