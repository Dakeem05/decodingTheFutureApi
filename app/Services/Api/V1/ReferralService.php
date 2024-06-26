<?php

namespace App\Services\Api\V1;

use App\Models\User;
use App\Models\UserPoint;

class ReferralService
{

    public function index (int $user_id)
    {
        $user = User::where('id', $user_id)->first();
        $referral_code = $user->referral_code;
        $downlines = User::where('referrer_code', $referral_code)->get();
        $return = [];
        foreach ($downlines as $downline) {
            $user_point = UserPoint::where('user_id', $downline->id)->first();
            $return[] = [
                "name" => $downline->name,
                "point" => $user_point->point,
            ];
        }
        return $return;
    }


}

