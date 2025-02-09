<?php

namespace App\Service;

use Carbon\Carbon;
use App\Constants\CommonConstant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Repository\LoanViewDetailsRepository;


class LoanViewDetailsService
{
    public function __construct(protected LoanViewDetailsRepository $loanViewDetailsRepository)
    {}
    public function loanViewDetailsProcess($viewDetailsRequestparam)
    {
        Log::channel('info')->info("view details request param: " . $viewDetailsRequestparam);
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');

        try {
            $userId = Redis::get('user_id');
            $loanId = $viewDetailsRequestparam->loan_id;
            $description = '';

            switch ($loanId) {
                case 1:
                    $description = CommonConstant::LOAN_DESCRIPTIONS['Personal'];
                    break;
                case 2:
                    $description = CommonConstant::LOAN_DESCRIPTIONS['Home'];
                    break;
                case 3:
                    $description = CommonConstant::LOAN_DESCRIPTIONS['Car']; 
                    break;
                case 4:
                    $description = CommonConstant::LOAN_DESCRIPTIONS['Business'];
                    break;
                case 5:
                    $description = CommonConstant::LOAN_DESCRIPTIONS['Education'];
                    break;
                default:
                    $description = 'Unknown Loan Type'; // Fallback description
            }            
            $loanDetailsInsertBo = [
                "user_id" => $userId,
                "loan_id" => $viewDetailsRequestparam->loan_id,
                "transaction_type"=> $viewDetailsRequestparam->loan_type,
                "transaction_amount"=> $viewDetailsRequestparam->loan_amount,
                "transaction_date" =>now(),
                "created_timestamp"=>now(),
                "description" => $description,
                "txnDate" => CommonConstant::getTxnDate()
            ];
            Log::channel('info')->info("[$currentDateTime] User ID: $userId - loan details insert bo: " . json_encode($loanDetailsInsertBo));
            $loanViewDetailsBo = $this->loanViewDetailsRepository->insertLoanViewDetails($loanDetailsInsertBo);
            if ($loanViewDetailsBo) {
                Log::channel('info')->info("[$currentDateTime] User ID: $userId - view details inserted successfully");
                return [
                    "message" => "View Details Inserted Successfully",
                    "status" => "success",
                    "data" => $loanViewDetailsBo
                ];
            } else {
                Log::channel('error')->error("[$currentDateTime] User ID: $userId - bank details not saved");
                return [
                    "message" => "Failed to save View Details",
                    "status" => CommonConstant::FAILED_MESSAGE,
                    "data" => []
                ];
            }


        }
        catch (\Exception $e) {
            Log::channel('error')->error("[$currentDateTime] User ID: $userId - Error occurred while inserting view details: " . $e->getMessage());
            return [
                "message" => "Failed to save View Details",
                "status" => CommonConstant::FAILED_MESSAGE,
                "data" => []
            ];
        }
    }
    
}
