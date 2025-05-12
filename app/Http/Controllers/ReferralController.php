<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\ReferralService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class ReferralController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    public function generateReferral()
    {
        $userId = Redis::get('user_id');
        
        $referral = $this->referralService->createReferralForUser($userId);

        return response()->json([
            'status' => true,
            'message' => 'Referral code generated successfully.',
            'data' => $referral
        ]);
    }
}

