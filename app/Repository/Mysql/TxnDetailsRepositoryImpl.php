<?php

namespace App\Repository\Mysql;

use App\Common\LogHelper;
use App\Models\LmsTransaction;
use App\Repository\TxnDetailsRepository;

class TxnDetailsRepositoryImpl implements TxnDetailsRepository
{
    public function __construct(protected LogHelper $logHelper) {}
    public function getUserTransactions($userId)
    {
        try {
            $this->logHelper->logInfo($userId,"Getting all loans");
            // Retrieve all loans with the necessary columns
            return LmsTransaction::select( 'id',
                'user_id',
                'loan_id',
                'transaction_type',
                'transaction_amount',
                'description',
                'txnDate'
            )
            ->where('is_show_flag', true)
            ->get();
        } catch (\Exception $e) {
            $this->logHelper->logCritical($e->getMessage(), 'Error getting user transactions');
            return [];
        }
    }

}
