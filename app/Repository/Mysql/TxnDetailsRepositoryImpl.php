<?php

namespace App\Repository\Mysql;

use App\Models\Transaction;
use App\Repository\TransactionRepository;

class TxnDetailsRepositoryImpl implements TxnDetailsRepository
{
    public function getUserTransactions($userId)
    {
        return Transaction::where('user_id', $userId)->orderBy('date', 'desc')->get();
    }
}
