<?php

namespace App\Repository;

interface ProfileDetailsRepository
{
    public function fetchProfileInformation();

    public function fetchCreditScore();

    public function fetchLoanHistory();
}
