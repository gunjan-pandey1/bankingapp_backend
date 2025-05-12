<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\RegisterService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Service\UpdateProfileService;
use App\Http\Requests\RegisterRequest;

class UpdateProfileController extends Controller
{
    public function __construct(protected UpdateProfileService $updateProfileService)
    {
    }

    public function updateProfileProcess(Request $request)
    {
        try {
            Log::channel('info')->info('update profile request', ['data' => $request]);
            
            $responseData = $this->updateProfileService->updateProfile($request);
            
            if ($responseData['status'] === 'success') {
                return response()->json([
                    'message' => 'Profile updated successfull',
                    'status' => 'success',
                    'data' => $responseData['data']
                ], 201);
            }

            $statusCode = $responseData['message'] === 'Profile already exists' ? 422 : 400;
            
            return response()->json([
                'message' => $responseData['message'],
                'status' => 'error',
                'errors' => ['email' => [$responseData['message']]]
            ], $statusCode);

        } catch (\Exception $exception) {
            Log::channel('error')->error('profile update failed', [
                'error' => $exception->getMessage(),
            ]);

            return response()->json([
                'message' => 'Profile update failed',
                'status' => 'error',    
                'errors' => ['server' => ['An unexpected error occurred']]
            ], 500);
        }
    }
}
