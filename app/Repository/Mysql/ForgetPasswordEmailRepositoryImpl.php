<?php

namespace App\Repository\Mysql;

use App\Models\User;
use App\Common\LogHelper;
use App\Repository\ForgetPasswordEmailRepository;

class ForgetPasswordEmailRepositoryImpl implements ForgetPasswordEmailRepository {

    public function __construct(protected LogHelper $logHelper) {}

    public function sendEmailExists(array $registerGetBo)
    {
        $email = $registerGetBo["email"];
        try {
            $this->logHelper->logInfo($email, 'Sending email to: '.$email);
            return User::where('email', $email)->exists();

            
        } catch (\Exception $e) {
            $this->logHelper->logCritical($email, 'Error occurred while sending email: '.$e->getMessage());
            return false;
        }
    }

}