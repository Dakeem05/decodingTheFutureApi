<?php

namespace App\Services\Api\V1;

use App\Models\User;
use App\Models\UserPoint;
use Carbon\Carbon;

class ClaimService
{

    public function claim (int $user_id, int $amount = 1000)
    {
        $user = User::where('id', $user_id)->first();
        $user_point = UserPoint::where('user_id', $user_id)->first();
        $last_claim_at = $user_point->last_claim_at;
        if ($last_claim_at == null) {
            $user_point->update([
                'point' => $user_point->point + $amount,
                'last_claim_at' => Carbon::now()
            ]);
            if ($user_point->point >= 40000) {
                if($user->referrer_code !== null){
                    if($user->referral_counted_at == null){
                        $this->rewardReferrer($user->referrer_code);
                        $user_point->update([
                            'referral_counted_at' => Carbon::now()
                        ]);
                        // return true;
                    }
                }
            }
            return true;
        } else {
            $due = $user_point->last_claim_at->addHours(24);
        
            if (Carbon::now()->greaterThanOrEqualTo($due)) {
                $user_point->update([
                    'point' => $user_point->point + $amount,
                    'last_claim_at' => Carbon::now()
                ]);
                if ($user_point->point >= 40000) {
                    if($user->referrer_code !== null){
                        if($user->referral_counted_at == null){
                            $this->rewardReferrer($user->referrer_code);
                            $user_point->update([
                                'referral_counted_at' => Carbon::now()
                            ]);
                            // return true;
                        }
                    }
                }
                return true;
            } else {
                return false;
            }
        }
    }

    private function rewardReferrer(string $referrer_code, int $amount = 2000)
    {
        $user = User::where('referral_code', $referrer_code)->first();
        $wallet = UserPoint::where('user_id', $user->id)->first();
        $wallet->update([
            'point' => $wallet->point + $amount,
        ]);
    }


}

