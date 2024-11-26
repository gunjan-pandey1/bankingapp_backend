<?php

namespace App\Repository\Mysql;

use App\Models\User;
use App\Common\LogHelper;
use App\Models\LmsUser;
use App\Repository\LoginRepository;

class LoginRepositoryImpl implements LoginRepository {

    public function __construct(protected LogHelper $logHelper) {}

    public function loginCreate(array $loginInsertBo)
    {
        $email = $loginInsertBo["email"];
        try {
            $this->logHelper->logInfo($email, message: 'Creating user: '.$email);
            return LmsUser::create($loginInsertBo);
        
        } catch (\Exception $e) {
            $this->logHelper->logCritical($loginInsertBo["email"], 'Error occurred while creating user: '.$e->getMessage());
            return false;
        }
    }

}