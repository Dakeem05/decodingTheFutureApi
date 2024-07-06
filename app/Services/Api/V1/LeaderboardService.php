<?php

namespace App\Services\Api\V1;

use App\Models\User;
use App\Models\UserPoint;

class LeaderboardService
{

    public function index (int $user_id)
    {
        $user = User::where('id', $user_id)->first();
        $user_count = UserPoint::count();
        $user_points = UserPoint::orderBy('point', 'desc')->get();
        $position = '';

        $data = [];
        
        foreach ($user_points as $key => $value) {
            if ($user->id == $value->user_id) {
              $position =  $key + 1;
            }
            if (($key + 1) <=  100)
            {
                $point = $value->point;
                $person = User::where('id', $value->user_id)->first();
                $referrals = User::where('referrer_code', $person->referral_code)->count();
                $data[] = [
                    'position' => $key + 1,
                    'name' => $person->name,
                    'referrals' => $referrals,
                    'point' => $point,
                ];
            }
        }
        $response = [
            'total' => $user_count,
            'position' => $position,
            'leaderboard' => $data
        ];
        return $response;
    }

    public function check ()
    {
        $users = User::all();

        foreach ($users as $key => $user) {
            $referrals = User::where('referrer_code', $user->referral_code)->get();
            foreach ($referrals as $key => $referral) {
                $points = UserPoint::where('user_id', $referral->id)->first();
                if ($points->point < 20000) {
                    $referrer_points = UserPoint::where('user_id', $user->id)->first();
                    $referrer_points->decrement('point', 2000);
                    $referral->forceDelete();
                    return true;
                }
                return false;
            }
        }
    }
}

