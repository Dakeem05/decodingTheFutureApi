<?php

namespace App\Services\Api\V1;

use App\Models\User;
use App\Models\UserPoint;
use Carbon\Carbon;

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
        // $users = User::where('email_verified_at', null)->get();
        // foreach ($users as $key => $user) {
        //     // if ($user->referrer_code !== null){
        //     //     $referrer = User::where('referral_code', $user->referrer_code)->first();
        //     //                 if($referrer !== null){
        //     //         // return $referrer;
        //     //         // return $user->referrer_code;
        //     //             $referrer_points = UserPoint::where('user_id', $referrer->id)->first();
        //     //         if($referrer_points !== null){
        //     //             $referrer_points->decrement('point', 2000);
        //     //             $user->forceDelete();
        //     //         }
        //     // }
        //                 $user->forceDelete();
        // // }
        // }
        
        $points = UserPoint::all();
         foreach ($points as $key => $point) {
            $point->update([
                'referral_counted_at' => Carbon::now()
            ]);
        }
        
        // $points = UserPoint::all();
        //  foreach ($points as $key => $point) {
        //     if ($point->point <= 2000) {
        //         $user = User::where('id', $point->user_id)->first();
        //         if ($user->referrer_code !== null) {
        //             $referrer = User::where('referral_code', $user->referrer_code)->first();
        //             if($referrer !== null){
        //             // return $referrer;
        //             // return $user->referrer_code;
        //                 $referrer_points = UserPoint::where('user_id', $referrer->id)->first();
        //             if($referrer_points !== null){
        //                 $referrer_points->decrement('point', 2000);
        //                 $user->forceDelete();
        //             }
                        
        //             }
        //         }
        //         $user->forceDelete();
        //     }
        // }
        
        
        // $users = User::all();

        // foreach ($users as $key => $user) {
        //     $referrals = User::where('referrer_code', $user->referral_code)->get();
        //     foreach ($referrals as $key => $referral) {
        //         $points = UserPoint::where('user_id', $referral->id)->first();
        //         if ($points->last_claim_at == null) {
        //         // if ($points->point < 30000) {
        //             $referrer_points = UserPoint::where('user_id', $user->id)->first();
        //             $referrer_points->decrement('point', 2000);
        //             $referral->forceDelete();
        //         }
        //         // return false;
        //         // return $points;
        //     }
        //             // return true;
        // }
    }
}

