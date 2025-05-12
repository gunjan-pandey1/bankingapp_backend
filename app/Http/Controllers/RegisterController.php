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
    }

    public function registerProcess(RegisterRequest $registerRequest)
    {
        try {
            Log::channel('info')->info('Registration attempt', ['data' => $registerRequest->safe()->all()]);
            
            $responseData = $this->registerService->register($registerRequest);
            
            if ($responseData['status'] === 'success') {
                return response()->json([
                    'message' => 'Registration successful',
                    'data' => $responseData['data']
                ], 201);
            }

            $statusCode = $responseData['message'] === 'Email is already registered' ? 422 : 400;
            
            return response()->json([
                'message' => $responseData['message'],
                'errors' => ['email' => [$responseData['message']]]
            ], $statusCode);

        } catch (\Exception $exception) {   
            Log::channel('error')->error('Registration failed', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Registration failed',
                'errors' => ['server' => ['An unexpected error occurred']]
            ], 500);
        }
    }
}
