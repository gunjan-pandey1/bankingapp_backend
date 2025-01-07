<?php

namespace App\Service;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use App\Repository\DashboardRepository;
use Illuminate\Support\Facades\Session;


class DashboardService
{
    public function __construct(protected DashboardRepository $dashboardRepository)
    {}
    public function dashboardDetails()
    {
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        $userId = Redis::get('user_id');        
        Log::channel('info')->info("DashboardService: User ID from session: " . $userId);

        try {
            // Credit Score API Call
            $url = 'https://seeking-alpha.p.rapidapi.com/symbols/get-ratings';
            $headers = [
                'x-rapidapi-host' => 'seeking-alpha.p.rapidapi.com',
                'x-rapidapi-key' => env('RAPIDAPI_KEY'), // Secure your API key using .env
                'Accept' => 'application/json',
            ];  
            $queryParams = [
                'symbol' => 'aapl', // 'aapl' as the default symbol
            ];
    
            $response = Http::withHeaders($headers)
                ->timeout(100) // Timeout in seconds
                ->withoutVerifying()
                ->get($url, $queryParams);
            
            Log::channel('info')->info('API Response for credit score', ['body' => $response->body()]);
    
            // Parse and check response
            $creditScoreResponse = json_decode($response->body(), true);
            $tickerId = $creditScoreResponse['data'][0]['attributes']['tickerId'];
            if (isset($tickerId)) {
                Log::channel('info')->info("[$currentDateTime] Credit Score API call successful, Ticker ID: " . json_encode($tickerId));
            } else {
                Log::channel('error')->error("[$currentDateTime] Credit Score API call failed: " . json_encode($creditScoreResponse));
                return [
                    "message" => "Failed to fetch credit score data",
                    "status" => "error",
                    "data" => []
                ];
            }
    
            // Get User Loans Widget
            Log::channel('info')->info("Calling getuserloanswidget with User ID: $userId");
            DB::enableQueryLog();
            $loansdbResponse = $this->dashboardRepository->getuserloanswidget($userId);
            Log::channel('info')->info('Query Log: ', DB::getQueryLog());
            Log::channel('info')->info("Response from getuserloanswidget: " . json_encode($loansdbResponse));
            if ($loansdbResponse) {
                Log::channel('info')->info("[$currentDateTime] User loans fetched successfully: " . json_encode($loansdbResponse));
            } else {
                Log::channel('error')->error("[$currentDateTime] Failed to fetch user loans for User ID: $userId");
                return [
                    "message" => "Failed to fetch user loans data",
                    "status" => "error",
                    "data" => []
                ];
            }
    
            // Get Next Payment Widget
            Log::channel('info')->info("Calling getnextpaymentwidget with User ID: $userId");
            $nextpaymentdbResponse = $this->dashboardRepository->getnextpaymentwidget($userId);
            Log::channel('info')->info("Response from getnextpaymentwidget: " . json_encode($nextpaymentdbResponse));
            if ($nextpaymentdbResponse) {
                Log::channel('info')->info("[$currentDateTime] Next payment data fetched successfully: " . json_encode($nextpaymentdbResponse));
            } else {
                Log::channel('error')->error("[$currentDateTime] Failed to fetch next payment for User ID: $userId");
                return [
                    "message" => "Failed to fetch next payment data",
                    "status" => "error",
                    "data" => []
                ];  
            }
    
            // Return all successful data
            return [
                "message" => "Data fetched successfully",
                "status" => "success",
                "data" => [
                    'tickerId' => $tickerId,
                    'loans' => $loansdbResponse,
                    'nextPayment' => $nextpaymentdbResponse,
                ],
            ];
        } catch (\Exception $e) {
            Log::channel('critical')->critical("[$currentDateTime] Error in DashboardService: " . $e->getMessage(), ['exception' => $e]);
            return [
                "message" => "An error occurred while fetching dashboard data",
                "status" => "error",
                "data" => []
            ];
        }
    }
}
