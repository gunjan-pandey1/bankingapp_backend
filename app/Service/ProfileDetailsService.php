<?php

namespace App\Service;

use App\Repository\ProfileDetailsRepository;
use Illuminate\Support\Facades\Log;

class ProfileDetailsService
{
    public function __construct(protected ProfileDetailsRepository $profileDetailsRepository)
    {}

    public function getProfileInformation()
    {
        try {
            $profile = $this->profileDetailsRepository->fetchProfileInformation();
            Log::channel('info')->info("ProfileService::getProfileInformation profile: " . $profile);

            return [
                "message" => "Profile information fetched successfully.",
                "status" => "success",
                "data" => $profile
            ];
        } catch (\Exception $exception) {
            Log::channel('critical')->critical("ProfileService error: " . $exception->getMessage());
            return [
                "status" => "error",
                "message" => "Failed to fetch profile information.",
                "data" => []
            ];
        }
    }

    public function getCreditScore()
    {
        try {
            $creditScore = $this->profileDetailsRepository->fetchCreditScore();
            Log::channel('info')->info("ProfileService::getCreditScore credit score: " . $creditScore);

            return [
                "message" => "Credit score fetched successfully.",
                "status" => "success",
                "data" => $creditScore
            ];
        } catch (\Exception $exception) {
            Log::channel('critical')->critical("ProfileService error: " . $exception->getMessage());
            return [
                "status" => "error",
                "message" => "Failed to fetch credit score.",
                "data" => []
            ];
        }
    }

    public function getLoanHistory()
    {
        try {
            $loanHistory = $this->profileDetailsRepository->fetchLoanHistory();
            Log::channel('info')->info("ProfileService::getLoanHistory loan history: " . $loanHistory);

            return [
                "message" => "Loan history fetched successfully.",
                "status" => "success",
                "data" => $loanHistory
            ];
        } catch (\Exception $exception) {
            Log::channel('critical')->critical("ProfileService error: " . $exception->getMessage());
            return [
                "status" => "error",
                "message" => "Failed to fetch loan history.",
                "data" => []
            ];
        }
    }
}
