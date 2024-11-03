<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\UserFormService;
use App\Http\Requests\UserFormRequest;
use Illuminate\Support\Facades\Log;

class UserFormController extends Controller
{
    public function __construct(protected UserFormService $userFormService)
    {}

    public function userFormProcess(UserFormRequest $userFormRequest)
    {
        try {
            Log::info($userFormRequest->all());

            // Process the form data through the service layer
            $responseData = $this->userFormService->userForm($userFormRequest->all());

            if ($responseData["status"] == "success") {
                return response()->json([
                    "message" => "User Created Successfully", 
                    "data" => $responseData["data"]
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong", 
                    "data" => $responseData["data"]
                ], 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, return the validation error message
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),  // Returns the detailed validation errors
            ], 422); // Unprocessable Entity HTTP code for validation errors
        } catch (\Exception $e) {
            // Log any other exceptions and return a generic error message
            Log::error('Error occurred: ' . $e->getMessage());
            return response()->json([
                'message' => 'An unexpected error occurred',
            ], 500);
        }
    }
}
