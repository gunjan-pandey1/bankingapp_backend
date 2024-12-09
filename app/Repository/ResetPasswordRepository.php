<?php

namespace App\Repository;

interface ResetPasswordRepository
{
    public function isValidToken(string $email, string $token): bool;

    public function updatePassword(string $email, string $newPassword): bool;
}
