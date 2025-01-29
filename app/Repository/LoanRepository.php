<?php

namespace App\Repository;

interface LoanRepository {

    public function getAllLoans($userId);
    public function getAllBanks($userId);
}   