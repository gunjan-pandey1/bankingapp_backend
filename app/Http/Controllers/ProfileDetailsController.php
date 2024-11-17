<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\ProfileDetailsService;
use App\Http\Requests\ProfileDetailsRequest;

class ProfileDetailsController extends Controller
{
    public function __construct(protected ProfileDetailsService $profileDetailsService)
    {
        $this->profileDetailsService = $profileDetailsService;
    }

    public function profileDetailsProcess(ProfileDetailsRequest $profileDetailsRequest)
    {
        try {
            $responseData = $this->profileDetailsService->profileDetails($profileDetailsRequest);
            if (strtolower($responseData['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'Profile details retrieved successfully', "data" => $responseData["data"]], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'Failed to retrieve profile details'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
