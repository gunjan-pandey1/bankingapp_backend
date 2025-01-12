<?php

namespace App\Service;

use App\Repository\TxnDetailsRepository;

class TxnDetailsService
{
    protected $txnDetailsRepository;

    public function __construct(TxnDetailsRepository $txnDetailsRepository)
    {
        $this->txnDetailsRepository = $txnDetailsRepository;
    }

    public function getUserTransactions($userId)
    {
        return $this->txnDetailsRepository->getUserTransactions($userId);
    }
}
