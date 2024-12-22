<?php

namespace App\Repository\Mysql;

use App\Models\LmsLoan;
use App\Repository\LoanRepository;

class LoanRepositoryImpl implements LoanRepository
{
    public function getActiveLoans()
    {
        return LmsLoan::where('is_show_flag', 1)
            ->where('status', 1)
            ->orderBy('created_date', 'desc')
            ->get();
    }
}