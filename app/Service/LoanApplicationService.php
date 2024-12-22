<?php

namespace App\Service;

use App\Models\LmsLoan;
use Carbon\Carbon;

class LoanApplicationService
{
    public function getActiveLoans()
    {
        return LmsLoan::where('is_show_flag', 1)
            ->where('status', 1)
            ->orderBy('created_date', 'desc')
            ->get()
            ->map(function($loan) {
                return [
                    'id' => $loan->id,
                    'type' => $loan->loan_type,
                    'amount' => number_format($loan->amount, 2),
                    'interest' => number_format($loan->interest_rate, 2),
                    'term' => $loan->duration_month,
                    'status' => 'Active',
                    'nextPayment' => Carbon::parse($loan->created_date)
                        ->addMonths(1)
                        ->format('M d, Y')
                ];
            });
    }
}