<?php

namespace App\Repository\Mysql;

use Carbon\Carbon;
use App\Models\LmsLoan;
use App\Common\LogHelper;
use App\Models\LmsBankDetails;
use App\Models\LmsTransaction;
use Illuminate\Support\Facades\Log;
use App\Repository\LoanHistoryRepository;

class LoanHistoryRepositoryImpl implements LoanHistoryRepository
{
    public function __construct(protected LogHelper $logHelper) {}

    /**
     * Get active loans from the database.
     *
     * @param array $loanGetBo
     * @return array|bool
     */
    public function getAllUserLoans($userId)
    {
        try {
            $this->logHelper->logInfo($userId,"Getting all user loans");
            // Retrieve all loans with the necessary columns
            return LmsLoan::select( 'id',
                'loan_type',
                'amount',
                'status',
                'duration_month'
            )
            ->where('is_show_flag', 1)
            ->get();
        } catch (\Exception $e) {
            $this->logHelper->logCritical($e->getMessage(), 'Error getting active loans');
            return [];
        }
    }


}