<?php

namespace App\Service;

use auth;
use Carbon\Carbon;
use App\Constants\CommonConstant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Repository\BankDetailsRepository;

class BankDetailsService
{
    public function __construct(protected BankDetailsRepository $bankDetailsRepository)
    {}
    public function saveBankDetails($bankDetailsRequestparam)
    {
        try {
            $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
            $userId = Redis::get('user_id');
            Log::channel('info')->info("BankDetailsService: User ID from redis: " . $userId); 
            
    
            // Prepare search criteria for existing bank details
            $bankDetailsGetBo = [
                "user_id" => $userId
            ];
            Log::channel('info')->info("Search criteria for existing bank details: " . json_encode($bankDetailsGetBo));
    
            // Check for existing bank details
            // $existingDetails = $this->bankDetailsRepository->getBankDetails($bankDetailsGetBo);
            // Log::channel('info')->info("Calling getBankDetails with: " . json_encode($bankDetailsGetBo));
    
            // Log::channel('info')->info("Bank details request param: " . json_encode($bankDetailsRequestparam));
            // Prepare bank details data for insert/update
            $bankDetailsInsertBo = [
                "user_id" => $userId,
                "account_holder_name" => $bankDetailsRequestparam->account_name,
                "account_number" => $bankDetailsRequestparam->account_number,
                "ifsc_code" => $bankDetailsRequestparam->ifsc_code,
                "bank_name" => $bankDetailsRequestparam->bank_name
            ];
    
            Log::channel('info')->info("Data to be processed: " . json_encode($bankDetailsInsertBo));
    
            // Update or create bank details based on existence
            if ($existingDetails) {
                Log::channel('info')->info("[$currentDateTime] User ID: $userId - bank details already exist");
                $bankDetailsDbResponse = $this->bankDetailsRepository->updateBankDetails($bankDetailsInsertBo);
                $action = "updated";
            } else {
                Log::channel('info')->info("[$currentDateTime] User ID: $userId - bank details created");
                $bankDetailsDbResponse = $this->bankDetailsRepository->createBankDetails($bankDetailsInsertBo);
                $action = "created";
            }
    
            // Log the response and return result
            if ($bankDetailsDbResponse) {
                Log::channel('info')->info("[$currentDateTime] User ID: $userId - bank details $action successfully");
                return [
                    "message" => "Bank Details $action Successfully",
                    "status" => "success",
                    "data" => $bankDetailsDbResponse
                ];
            } else {
                Log::channel('error')->error("[$currentDateTime] User ID: $userId - bank details not saved");
                return [
                    "message" => "Failed to save bank details",
                    "status" => CommonConstant::FAILED_MESSAGE,
                    "data" => []
                ];
            }
        } catch (\Exception $e) {
            Log::channel('error')->error("[$currentDateTime] Error in BankDetailsService   11: ".$e->getMessage(), ['exception' => $e]);
            return [
                "message" => "An error occurred while saving bank details",
                "status" => "error",
                "data" => []
            ];
        }
    }
}
