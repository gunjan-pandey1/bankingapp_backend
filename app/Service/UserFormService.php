<?php

namespace App\Service;

use App\Repository\UserFormRepository;

class UserFormService {

    public function __construct(protected UserFormRepository $userFormRepository)
    {}

    public function userForm(array $userFormParams)
    {
        $userFormInsertBo = [
            'username' => $userFormParams['username'],
            'email' => $userFormParams['email'],
            'password' => bcrypt($userFormParams['password']),  // Ensure password is hashed
            'address' => $userFormParams['address'],
            'mobileNo' => $userFormParams['mobileNo'],
            'gender' => $userFormParams['gender'],
        ];

        $userFormDbResponse = $this->userFormRepository->userFormCreate($userFormInsertBo);

        if ($userFormDbResponse) {
            return ["status" => "success", "data" => $userFormDbResponse];
        } else {
            return ["status" => "error", "data" => null];        
        }
    }
}
