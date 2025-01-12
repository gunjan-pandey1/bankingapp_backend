<?php
namespace App\Repository\Mysql;

use App\Models\Referral;
use App\Repository\ReferralRepository;

class ReferralRepositoryImpl implements ReferralRepository
{
    public function createReferral($userId, $referralCode)
    {
        return Referral::create([
            'user_id' => $userId,
            'referral_code' => $referralCode,
        ]);
    }

    public function getReferralByCode($referralCode)
    {
        return Referral::where('referral_code', $referralCode)->first();
    }

    public function updateReferral($referralId, $data)
    {
        return Referral::where('id', $referralId)->update($data);
    }
}
