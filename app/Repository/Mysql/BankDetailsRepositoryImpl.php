<?php
namespace App\Repository\Mysql;

use App\Models\BankDetail;
use App\Common\LogHelper;
use App\Models\LmsBankDetails;
use Illuminate\Support\Facades\Log;
use App\Repository\BankDetailsRepository;

class BankDetailsRepositoryImpl implements BankDetailsRepository
{
    public function __construct(protected LogHelper $logHelper) {}

    public function createBankDetails(array $bankDetailsInsertBo)
    {
        Log::info("hiiii in the bank details repository impl");
        try {
            $this->logHelper->logInfo(json_encode($bankDetailsInsertBo), 'Creating bank details: '.json_encode($bankDetailsInsertBo));

            $bankDetails = LmsBankDetails::create($bankDetailsInsertBo);

            return $bankDetails->toArray();
        } catch (\Exception $e) {
            $this->logHelper->logCritical($e->getMessage(), 'Error creating bank details: '.json_encode($bankDetailsInsertBo));
            return false;
        }
    }

    public function updateBankDetails(array $bankDetailsUpdateBo)
    {
        Log::info("hiiii in the bank details repository impl in update");
        try {

            $this->logHelper->logInfo(json_encode($bankDetailsUpdateBo), 'Updating bank details: '.json_encode($bankDetailsUpdateBo));

            $bankDetails = LmsBankDetails::where('user_id', $bankDetailsUpdateBo['user_id'])
                ->first();
                
            if ($bankDetails) {
                $bankDetails->update($bankDetailsUpdateBo);
                return $bankDetails->toArray();
            }
            
            return false;
        } catch (\Exception $e) {
            $this->logHelper->logCritical($e->getMessage(), 'Error updating bank details: '.json_encode($bankDetailsUpdateBo));
            return false;
        }
    }

    public function getBankDetails(array $bankDetailsGetBo)
    {
        Log::channel('info')->info("hiiii in the bank details repository impl in get");
        $userId = $bankDetailsGetBo['user_id'] ?? null;
        Log::channel('info')->info("User ID: $userId");
        try {
            $this->logHelper->logInfo($userId, 'Getting bank details for user: '.$userId);
    
            $bankDetails = LmsBankDetails::where('user_id', $userId)->first();

            return $bankDetails ? $bankDetails->toArray() : false;
        } catch (\Exception $e) {
            $this->logHelper->logCritical($userId, 'Error getting bank details for user: '.$userId);
            return false;
        }
    }
}
