<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardRequest;
use App\Service\DashboardService;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function dashboardProcess(DashboardRequest $dashboardRequest)
    {
        try {
            $responseData = $this->dashboardService->dashboardDetails($dashboardRequest);
            if (strtolower($responseData['status']) == 'success') {
                return response()->json(['success' => true, 'message' => 'Dashboard details retrieved successfully', 'data' => $responseData['data']], 200);
            } else {
                return response()->json(['success' => false,  'message' => 'Failed to retrieve dashboard details'], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
