<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Constants\CommonConstant;

class DashboardController extends Controller
{
    /**
     * Fetch stock ratings using the Seeking Alpha API
     */
    public function dashboard(Request $request)
    {
        $url = 'https://seeking-alpha.p.rapidapi.com/symbols/get-ratings';
        $headers = [
            'x-rapidapi-host' => 'seeking-alpha.p.rapidapi.com',
            'x-rapidapi-key' => env('RAPIDAPI_KEY'), // Secure your API key using .env
            'Accept' => 'application/json',
        ];  
        $queryParams = [
            'symbol' => 'aapl', // 'aapl' as the default symbol
        ];
        try {
            Log::channel('info')->info('Fetching credit score from API', ['query' => $queryParams]);

            $response = Http::withHeaders($headers)
                ->timeout(100) // Timeout in seconds
                // ->asForm()
                ->withoutVerifying()
                ->get($url, $queryParams);

            Log::channel('info')->info('API Response for credit score', ['body' => $response->body()]);

            // Parse and check response
            $responseBody = json_decode($response->body(), true);

            if ($responseBody === null) {
                Log::channel('error')->error('Invalid JSON response or empty body', ['response' => $response->body()]);

                return response()->json([
                    'status' => CommonConstant::FAILED_MESSAGE,
                    'message' => 'Invalid response from API',
                ], 500);
            }

            // Successful API response
            return response()->json([
                'status' => CommonConstant::SUCCESS_MESSAGE,
                'data' => $responseBody,
            ], 200);

        } catch (\Exception $e) {
            Log::channel('critical')->critical('Exception occurred while fetching credit score', [
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => CommonConstant::FAILED_MESSAGE,
                'message' => 'An error occurred while fetching credit score',
                'error' => $e->getMessage(),
            ], 500);
        } finally {
            Log::channel('info')->info('credit score API call ended');
        }
    }
}
