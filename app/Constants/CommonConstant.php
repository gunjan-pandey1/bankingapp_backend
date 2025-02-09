<?php

namespace App\Constants;

use Carbon\Carbon;

class CommonConstant
{


   
    public const SUCCESS_CODE = 200;

    public const FAILED_CODE = 500;

    public const SUCCESS_MESSAGE = 'success';

    public const FAILED_MESSAGE = 'failed';

    public const TXN_SUCCESS_MESSAGE = 'SUCCESS';

    public const TXN_FAILED_MESSAGE = 'FAILED';

    public const ERROR = 'error';


    public const TXN_PENDING_MESSAGE = 'PENDING';

    public const SUCCESS_FLAG = true;

    public const FAILED_FLAG = false;

    public const SUPPLY_TYPE_PAY1 = 1;

    public const SUPPLY_TYPE_LAPU_BAL = 2;

    public const TXN_TYPE_PAY1 = 1;

    public const TXN_TYPE_VENDOR = 2;

    public const SERVICE_ID = 61;

    public const SERVER = 'recharge_supply';


    public const LOAN_DESCRIPTIONS = [
        'Personal' => 'Loan taken for personal expenses such as medical bills, travel, or home renovations.',
        'Home' => 'Loan used for purchasing a new home or refinancing an existing mortgage.',
        'Business' => 'Loan acquired to finance business operations, expansion, or startup costs.',
        'Education' => 'Loan intended to cover tuition fees, books, and other educational expenses.',
        'Car' => 'Loan specifically for purchasing a vehicle, helping you drive your dreams.',
    ];
    public static function getTxnDate(): string
    {
        return Carbon::now()->format('Y-m-d H:i:s');
    }

}
