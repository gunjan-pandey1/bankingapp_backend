<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserDetailsRequest;
use App\Service\UserDetailsService;

class UserDetailsController extends Controller
{
    public function __construct(protected UserDetailsService $userDetailsService)
    {
        $this->userDetailsService = $userDetailsService;
    }

    public function userDetailsProcess(UserDetailsRequest $userDetailsRequest)
    {
        try {
            $responseData = $this->userDetailsService->userDetails($userDetailsRequest);
            if (strtolower($responseData['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'User details retrieved successfully', 'data' => $responseData['data']], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'Failed to retrieve user details'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
