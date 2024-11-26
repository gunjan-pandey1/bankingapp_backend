<?php

namespace App\Constants;

use Carbon\Carbon;

class CommonConstant
{
    public const DAILY_HOME_LIMIT = 1;

    public const PERDAY_LIMIT = 1;

    public const DAILY_ROMING_LIMIT = 1;

    public const BLOCK_AMOUNT = 1;

    public const IS_MANUAL = 1;

    public const IS_NOT_MANUAL = 0;

    public const IS_SHOW_FLAG = 1;

    public const IS_NOT_SHOW_FLAG = 0;

    public const IS_ACTIVE_FLAG = 1;

    public const IS_NOT_ACTIVE_FLAG = 0;

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

    public const METHOD = 'walletApi';

    public const TYPE = 'cr';

    public const SETTLE_FLAG = 1;

    public const SOURCE = 'web';

    public const SERVICE_CHARGE = 0;

    public const COMMISSION = 0;

    public const TAX = 0;

    public const PAYMENT_DESCRIPTION = 'Recharge Supply Payment';

    public const COM_DESCRIPTION = 'Recharge Supply Payment Commission';

    public const PRODUCT_AIRTEL_ID = 2;

    public const CIRCLE_FLAG = 1;

    public const AIRTEL_COMMISSION = 0.029;

    public const SHORT_NAME_AIRTEL = 'AT';

    public const SHORT_NAME_JIO = 'JO';

    public static function getTxnDate(): string
    {
        return Carbon::now()->format('Y-m-d H:i:s');
    }

    public static function getVendorId(): string
    {
        return config('app.lapuApiUserId');
    }
}
