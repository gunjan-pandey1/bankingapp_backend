<?php

namespace App\Http\Controllers;

use App\Service\RegisterService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    public function __construct(protected RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function registerProcess(RegisterRequest $registerRequest)
    {
        Log::info("RegisterController::registerProcess", $registerRequest->toArray());
        try {
            $responseData = $this->registerService->register($registerRequest);
            if (strtolower($responseData['status']) == 'success') {
                 Log::channel('info')->info("RegisterController", $responseData);
                
                return response()->json(['success' => true, 'message' => 'Registration successful', 'data' => $responseData['data']], 200);
            } else {
                Log::channel('error')->error("RegisterController", $responseData);

                return response()->json(['success' => false,  'message' => 'Registration failed'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
