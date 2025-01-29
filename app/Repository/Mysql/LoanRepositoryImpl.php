<?php

namespace App\Repository\Mysql;

use Carbon\Carbon;
use App\Models\LmsLoan;
use App\Common\LogHelper;
use App\Models\LmsBankDetails;
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
            $this->logHelper->logInfo($userId,"Getting all loans");
            // Retrieve all loans with the necessary columns
            return LmsLoan::select(
                'loan_type',
                'amount',
                'interest_rate',
                'duration_month',
                'status',
                'created_date',
                'updated_date'
            )
            ->where('is_show_flag', true)
            ->get();
        } catch (\Exception $e) {
            $this->logHelper->logCritical($e->getMessage(), 'Error getting active loans');
            return [];
        }
    }

    public function getAllBanks($userId)
    {
        try {
            $this->logHelper->logInfo($userId,"Getting bank details");
            return LmsBankDetails::select(
                'account_number',
                'ifsc',
                'bank_name',
                'branch_name'
            )
            ->where('user_id', $userId)
            ->get();
        } catch (\Exception $e) {
            $this->logHelper->logCritical($e->getMessage(), 'Error getting bank details');
            return [];
        }
    }

}