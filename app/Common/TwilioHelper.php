<?php

namespace App\Common;

use Twilio\Rest\Client as TwilioClient;

class TwilioHelper
{
    public function sendTwilioSms($to, $messageBody)
    {
        $sid = '';
        $token = '';
        $from = '';

        $twilio = new TwilioClient($sid, $token);
        return $twilio->messages->create($to, [
            'from' => $from,
            'body' => $messageBody,
        ]);
    }
}