<?php

namespace App\Repository\Mysql;

use App\Common\LogHelper;
use App\Models\LmsLoanRepayment;
use App\Repository\DashboardRepository;
use App\Models\LmsLoan;

class DashboardRepositoryImpl implements DashboardRepository
{
    protected LogHelper $logHelper;

    public function __construct(LogHelper $logHelper)
    {
        $this->logHelper = $logHelper;
    }

    public function getuserloanswidget($userId)
    {
        $this->logHelper->logInfo(['user_id' => $userId],"Getting user loans widget" );
        return LmsLoanRepayment::selectRaw('count(user_id) as user_count, total_loan_amount')
        ->where('is_show_flag', 1)
        ->where('status', 1)
        ->groupBy('user_id')
        ->get();/// multiple data, first means first data of db, count means 120,12..... 
    }

    public function getnextpaymentwidget($userId)
    {
        $this->logHelper->logInfo(['user_id' => $userId],"Gettingq next payment widget" );
        return LmsLoanRepayment::select('emi_amount_due', 'due_date')->where('user_id', $userId)->where('is_show_flag', 1)->where('status', 1)->get();
    }
}