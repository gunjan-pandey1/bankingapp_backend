<?php

namespace App\Repository;

interface UpdateProfileRepository
{
    public function findUserById(int $userId);
    public function updateUserProfile(int $userId, array $userProfileData);
}
