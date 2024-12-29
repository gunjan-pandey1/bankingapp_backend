<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants\CommonConstant;
use App\Service\DashboardService;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Fetch stock ratings using the Seeking Alpha API
     */
    public function dashboard()
    {
        try {
            Log::channel('info')->info("DashboardController: dashboard function called");
            $responseData = $this->dashboardService->dashboardDetails();
            Log::channel('info')->info("DashboardController: " . json_encode($responseData));

            // Return the response from the service directly
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

        } catch (\Exception $e) {
            Log::channel('critical')->critical("DashboardController error: " . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching dashboard data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}