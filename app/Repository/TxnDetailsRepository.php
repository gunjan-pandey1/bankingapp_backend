<?php

namespace App\Repository;

interface TxnDetailsRepository
{
    public function getUserTransactions($userId);
}

