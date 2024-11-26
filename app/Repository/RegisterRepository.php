<?php

namespace App\Repository;

interface RegisterRepository {

    public function registerCreate(array $registerInsertBo);

    public function registerGet(array $registerGetBo);
}