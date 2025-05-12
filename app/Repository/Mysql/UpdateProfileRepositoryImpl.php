<?php

namespace App\Repository\Mysql;

use App\Models\User;
use App\Common\LogHelper;
use App\Models\LmsUser;
use Illuminate\Support\Facades\Log;
use App\Repository\UpdateProfileRepository;

class UpdateProfileRepositoryImpl implements UpdateProfileRepository
{
    public function __construct(protected LogHelper $logHelper) {}

    public function findUserById(int $userId)
    {
        Log::channel('info')->info("Fetching user by ID: $userId");
        try {
            return LmsUser::find($userId);
        } catch (\Exception $e) {
            Log::channel('error')->error("Error fetching user by ID: $userId - " . $e->getMessage());
            return null;
        }
    }


    public function updateUserProfile(int $userId, array $userProfileData)
    {
        Log::channel('info')->info("Updating user profile for user ID: $userId", ['data' => $userProfileData]);
        try {
            $user = LmsUser::find($userId);
            if (!$user) {
                Log::channel('error')->error("User not found with ID: $userId");
                return false;
            }

            $user->update($userProfileData);
            return $user->refresh()->toArray();
        } catch (\Exception $e) {
            Log::channel('error')->error("Error updating user profile for user ID: $userId - " . $e->getMessage());
            return false;
        }
    }
}
