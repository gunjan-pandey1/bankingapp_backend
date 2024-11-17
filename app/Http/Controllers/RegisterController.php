<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\RegisterService;

class RegisterController extends Controller
{
    public function __construct(protected RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function registerProcess(RegisterRequest $registerRequest)
    {
        try {
            $responseData = $this->registerService->register($registerRequest);
            if (strtolower($responseData['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'Registration successful', "data" => $responseData["data"]], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'Registration failed'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
