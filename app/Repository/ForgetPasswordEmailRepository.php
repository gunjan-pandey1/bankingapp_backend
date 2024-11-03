<?php

namespace App\Repository;

interface ForgetPasswordEmailRepository {

    public function sendEmailExists(array $sendEmail);
}