<?php
namespace App\Service;

use App\Repository\ReferralRepository;
use App\Helpers\ReferralHelper;

class ReferralService
{
    protected $referralRepository;

    public function __construct(ReferralRepository $referralRepository)
    {
        $this->referralRepository = $referralRepository;
    }

    public function createReferralForUser($userId)
    {
        $referralCode = ReferralHelper::generateReferralCode();
        return $this->referralRepository->createReferral($userId, $referralCode);
    }

    public function validateReferralCode($referralCode)
    {
        return $this->referralRepository->getReferralByCode($referralCode);
    }

    public function claimReward($referralId)
    {
        return $this->referralRepository->updateReferral($referralId, ['reward_claimed' => true]);
    }
}
