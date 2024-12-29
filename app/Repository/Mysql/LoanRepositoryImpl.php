<?php

namespace App\Repository\Mysql;

use Carbon\Carbon;
use App\Models\LmsLoan;
use App\Common\LogHelper;
use App\Repository\LoanRepository;
use Illuminate\Support\Facades\Log;

class LoanRepositoryImpl implements LoanRepository
{
    public function __construct(protected LogHelper $logHelper) {}

    /**
     * Get active loans from the database.
     *
     * @param array $loanGetBo
     * @return array|bool
     */
    public function getAllLoans($userId)
    {
        try {
            $this->logHelper->logInfo(['user_id' => $userId],"Getting all loans");
            // Retrieve all loans with the necessary columns
            return LmsLoan::select(
                'loan_type',
                'amount',
                'interest_rate',
                'duration_month',
                'status',
                'next_payment_date',
                'created_date',
                'updated_date'
            )
            ->where('user_id', $userId)
            ->where('is_show_flag', true)
            ->get();
        } catch (\Exception $e) {
            $this->logHelper->logCritical($e->getMessage(), 'Error getting active loans');
            return [];
        }
    }

}