<?php

namespace App\Common;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LogHelper
{
    public function logError($userId, $message, array $context = [])
    {
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        Log::channel('error')->error("[$currentDateTime] User ID: $userId - $message", $context);
    }

    public function logInfo($userId, $message, array $context = [])
    {
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        Log::channel('info')->info("[$currentDateTime] User ID: $userId - $message", $context);
    }

    public function logCritical($userId, $message, array $context = [])
    {
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        Log::channel('critical')->critical("[$currentDateTime] User ID: $userId - $message", $context);
    }
}
