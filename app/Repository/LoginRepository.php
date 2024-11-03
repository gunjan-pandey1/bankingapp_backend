<?php

namespace App\Repository;

interface LoginRepository {

    public function loginCreate(array $loginInsertBo);
}