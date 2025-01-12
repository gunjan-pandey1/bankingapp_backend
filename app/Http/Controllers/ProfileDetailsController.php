<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Service\ProfileDetailsService;

class ProfileDetailsController extends Controller
{
    public function __construct(protected ProfileDetailsService $profileDetailsService)
    {
        $this->profileDetailsService = $profileDetailsService;
    }

    // Fetch logged-in user's profile information
    public function getProfileInformation()
    {
        try {
            $responseData = $this->profileDetailsService->getProfileInformation();
            Log::channel('info')->info("ProfileController::getProfileInformation response: " . $responseData);

            if ($responseData['status'] === 'success') {
                return response()->json([
                    'status' => true,
                    'message' => $responseData['message'],
                    'data' => $responseData['data'],
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $responseData['message'],
                    'data' => [],
                ], 500);
            }
        } catch (\Exception $exception) {
            Log::error("ProfileController error: " . $exception->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching profile information',
                'data' => []
            ], 500);
        }
    }

    // Fetch user's credit score
    public function getCreditScore()
    {
        try {
            Log::channel('info')->info("ProfileController::getCreditScore");
            $responseData = $this->profileDetailsService->getCreditScore();

            if ($responseData['status'] === 'success') {
                return response()->json([
                    'status' => true,
                    'message' => $responseData['message'],
                    'data' => $responseData['data'],
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $responseData['message'],
                    'data' => [],
                ], 500);
            }
        } catch (\Exception $exception) {
            Log::error("ProfileController error: " . $exception->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching credit score',
                'data' => []
            ], 500);
        }
    }

    // Fetch loan history
    public function getLoanHistory()
    {
        try {
            $responseData = $this->profileDetailsService->getLoanHistory();
            Log::channel('info')->info("ProfileController::getLoanHistory");

            if ($responseData['status'] === 'success') {
                return response()->json([
                    'status' => true,
                    'message' => $responseData['message'],
                    'data' => $responseData['data'],
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $responseData['message'],
                    'data' => [],
                ], 500);
            }
        } catch (\Exception $exception) {
            Log::error("ProfileController error: " . $exception->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching loan history',
                'data' => []
            ], 500);
        }
    }
}
