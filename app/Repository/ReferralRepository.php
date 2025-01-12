<?php
namespace App\Repository;

interface ReferralRepository
{
    public function createReferral($userId, $referralCode);
    public function getReferralByCode($referralCode);
    public function updateReferral($referralId, $data);
}
?>
