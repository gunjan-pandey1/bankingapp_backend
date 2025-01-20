<?php

namespace App\Repository\Mysql;

use App\Models\LmsTransaction;
use App\Repository\TransactionRepository;

class TxnDetailsRepositoryImpl implements TxnDetailsRepository
{
    public function getUserTransactions($userId)
    {
        return LmsTransaction::where('user_id', $userId)->orderBy('date', 'desc')->get();
    }
}
