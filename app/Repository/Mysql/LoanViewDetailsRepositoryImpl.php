<?php
namespace App\Repository\Mysql;

use App\Common\LogHelper;
use App\Models\LmsBankDetails;
use App\Models\LmsTransaction;
use Illuminate\Support\Facades\Log;
use App\Repository\LoanViewDetailsRepository;

class LoanViewDetailsRepositoryImpl implements LoanViewDetailsRepository
{
    public function __construct(protected LogHelper $logHelper) {}

    public function insertLoanViewDetails($loanDetailsInsertBo)
    {
        Log::channel('info')->info("loan details insert bo " . json_encode($loanDetailsInsertBo));
        try {
            $this->logHelper->logInfo(json_encode($loanDetailsInsertBo), 'Creating loan view details: '.json_encode($loanDetailsInsertBo));
            $bankDetails = LmsTransaction::create($loanDetailsInsertBo);
            return $bankDetails->toArray();
        } catch (\Exception $e) {
            $this->logHelper->logCritical($e->getMessage(), 'Error creating loan view details: '.json_encode($loanDetailsInsertBo));
            return false;
        }
    }
}

?>