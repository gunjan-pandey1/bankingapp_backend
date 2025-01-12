<?php
namespace App\Helpers;

use Illuminate\Support\Str;

class ReferralHelper
{
    public static function generateReferralCode()
    {
        return strtoupper(Str::random(8));
    }
}

?>