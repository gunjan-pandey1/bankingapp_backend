<?php

namespace App\Repository;

interface LoanHistoryRepository {
    public function getAllUserLoans($userId);
}   