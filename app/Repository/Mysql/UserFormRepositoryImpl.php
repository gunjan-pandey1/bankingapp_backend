<?php

namespace App\Repository\Mysql;

use App\Models\User;
use App\Common\LogHelper;
use App\Repository\UserFormRepository;

class UserFormRepositoryImpl implements UserFormRepository {

    public function __construct(protected LogHelper $logHelper) {}


    public function userFormCreate(array $userFormInsertBo)
    {
        try{
            $this->logHelper->logInfo('User form data received', $userFormInsertBo);
            return User::create($userFormInsertBo);
        } catch (\Exception $e) {
            $this->logHelper->logCritical('Error in userFormCreate', ['exception' => $e]);
            return false;
        }
    }
}
