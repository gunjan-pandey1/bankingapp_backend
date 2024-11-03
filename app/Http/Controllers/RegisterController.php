<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Service\RegisterService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    public function __construct(protected RegisterService $registerService)
    {}

    public function registerprocess(RegisterRequest $registerRequest)
    {
        try {
            Log::info($registerRequest->all());
            $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
            $responseData = $this->registerService->register($registerRequest);

            // Use json_encode or structured logging for arrays
            Log::channel('error')->error("[$currentDateTime] registration_error:", $responseData);
            
            if ($responseData["status"] == "success") {
                return response()->json(["message" => "Register successful", "data" => $responseData["data"]], 201); // Success response
            } else if ($responseData["message"] == "Email is already registered") {
                return response()->json(["message" => "Email already exists", "errors" => $responseData["message"]], 422); // Email already exists response
            } else {
                return response()->json(["message" => "Register failed", "errors" => $responseData["message"]], 422); // General failure
            }
        } catch (\Exception $e) {
            Log::channel('error')->error("[$currentDateTime] Error occurred: ".$e->getMessage());
            return response()->json(["message" => "An error occurred while registering user", "errors" => ["internal_error" => "Error occurred while registering user"]], 500); // Server error response
        } 
    }
}
