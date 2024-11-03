<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Service\ForgetPasswordService;

class ForgotPasswordController extends Controller
{
    protected $forgetPasswordService;

    public function __construct(ForgetPasswordService $forgetPasswordService)
    {
        $this->forgetPasswordService = $forgetPasswordService;
    }

    public function sendResetLinkEmail(Request $request)
    {
        try {
            $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
            $responseData = $this->forgetPasswordService->forgetPassword($request);

            // Use json_encode or structured logging for arrays
            Log::channel('error')->error("[$currentDateTime] forgot_password_error:", $responseData);
        
            if ($responseData["status"] == "success") {
                return response()->json(["message" => "Reset link sent", "data" => $responseData["data"]], 201); // Success response
            } else if ($responseData["message"] == "Email does not exist") {
                return response()->json(["message" => "Email does not exist", "errors" => $responseData["message"]], 422); // Email already exists response
            }else {
                return response()->json(["message" => "Failed to send reset link", "errors" => $responseData["message"]], 422); // General failure
            }
        } catch (\Exception $e) {
            Log::channel(`error`)->error("[$currentDateTime] Error occurred: ".$e->getMessage());
            return response()->json(["message" => "An error occurred while resetting user", "errors" => ["internal_error" => "Error occurred while resetting user"]], 500); // Server error response
        }        
    }
}