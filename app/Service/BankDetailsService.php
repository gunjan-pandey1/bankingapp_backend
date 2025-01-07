<?php

namespace App\Service;

use Carbon\Carbon;
use App\Constants\CommonConstant;
use Illuminate\Support\Facades\Log;
use App\Repository\BankDetailsRepository;

class BankDetailsService
{
    public function __construct(protected BankDetailsRepository $bankDetailsRepository)
    {}
    public function saveBankDetails($bankDetailsRequestparam)
    {
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        try {
            $userId = auth()->id();
            $bankDetailsGetBo = [
                "user_id" => $userId
            ];
            Log::channel('info')->info("[$currentDateTime]: Checking existing bank details: " . json_encode($bankDetailsGetBo));
            $existingDetails = $this->bankDetailsRepository->getBankDetails($bankDetailsGetBo);

            $bankDetailsInsertBo = [
                "user_id" => $userId,
                "account_holder_name" => $bankDetailsRequestparam->account_name,
                "account_number" => $bankDetailsRequestparam->account_number,
                "ifsc_code" => $bankDetailsRequestparam->ifsc_code,
                "bank_name" => $bankDetailsRequestparam->bank_name
            ];

            Log::channel('info')->info("Data to be processed: " . json_encode($bankDetailsInsertBo));
            if ($existingDetails) {
                Log::channel('error')->error("[$currentDateTime]: Bank details already exist for user: " . json_encode($existingDetails));
                return ['status' => 'error', 'message' => 'Bank details already exist for user', "data" => []];
            }
            
            if ($existingDetails) {
                $bankDetailsDbResponse = $this->bankDetailsRepository->updateBankDetails($bankDetailsInsertBo);
            } else {
                $bankDetailsDbResponse = $this->bankDetailsRepository->createBankDetails($bankDetailsInsertBo);
            }

            if ($bankDetailsDbResponse) {
                Log::channel('info')->info("[$currentDateTime] User ID: $userId - bank details saved successfully");
                return [
                    "message" => "Bank Details Saved Successfully",
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
            Log::channel('error')->error("[$currentDateTime] Error in BankDetailsService: ".$e->getMessage(), ['exception' => $e]);
            return [
                "message" => "An error occurred while saving bank details",
                "status" => "error",
                "data" => []
            ];
        }
        
    }
}
