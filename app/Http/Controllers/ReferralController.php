<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\ReferralService;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    public function generateReferral()
    {
        $userId = Auth::id();
        $referral = $this->referralService->createReferralForUser($userId);

        return response()->json([
            'status' => true,
            'message' => 'Referral code generated successfully.',
            'data' => $referral
        ]);
    }

    public function validateReferral(Request $request)
    {
        $referralCode = $request->get('referral_code');
        $referral = $this->referralService->validateReferralCode($referralCode);

        if ($referral) {
            return response()->json([
                'status' => true,
                'message' => 'Referral code is valid.',
                'data' => $referral
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid referral code.'
            ], 400);
        }
    }
}

