<?php

namespace App\Repository\Mysql;

use App\Models\User;
use App\Models\LmsUser;
use App\Common\LogHelper;
use Illuminate\Support\Facades\Log;
use App\Repository\RegisterRepository;

class RegisterRepositoryImpl implements RegisterRepository
{
    public function __construct(protected LogHelper $logHelper) {}

    /**
     * Create a new user record in the database.
     *
     * @param array $registerInsertBo
     * @return array|bool
     */
    public function registerCreate(array $registerInsertBo)
    {
        try {
            // Log the incoming registration data in JSON format
            $this->logHelper->logInfo(json_encode($registerInsertBo), 'Creating user: '.json_encode($registerInsertBo));

            // Create a new user in the database
            $user = LmsUser::create($registerInsertBo);

            // Return the created user data if successful
            return $user->toArray();
        } catch (\Exception $e) {
            // Log the error in a critical log level
            $this->logHelper->logCritical($e->getMessage(), 'Error creating user: '.json_encode($registerInsertBo));
            return false;
        }
    }

    /**
     * Get a user by email.
     *
     * @param array $registerGetBo
     * @return array|bool
     */
    public function registerGet(array $registerGetBo)
    {
        $email = $registerGetBo["email"];
        $userId = $registerGetBo["id"];
        $this->logHelper->logInfo(json_encode($registerGetBo), " get data: ".$registerGetBo);

        try {
            // Log the attempt to retrieve a user
            $this->logHelper->logInfo($userId, 'Getting user with email: '.$email);

            // Retrieve the user by email
            $user = LmsUser::where('email', $email)->first();
            // If a user is found, return the user data, else return false
            return $user ? $user->toArray() : false;
        } catch (\Exception $e) {
            // Log any errors that occur
            $this->logHelper->logCritical($userId, 'Error getting user with email: '.$email);
            return false;
        }
    }
}
