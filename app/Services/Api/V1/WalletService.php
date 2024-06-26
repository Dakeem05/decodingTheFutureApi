<?php

namespace App\Services\Api\V1;

use App\Models\UserPoint;
use App\Models\Wallet;

class WalletService
{
    public function createWallet(int $user_id)
    {
        return UserPoint::create([
            'user_id' => $user_id,
            'point' => 1000
        ]);
    }



    // public function getWalletBalance(int $user_id)
    // {
    //     $wallet = Wallet::where('user_id', $user_id)->first();

    //     if ($wallet) {
    //         return $wallet->main_balance;
    //     }

    //     return 0;
    // }

    // public function updateWalletBalance(int $user_id, float $amount, string $type)
    // {
    //     $wallet = Wallet::where('user_id', $user_id)->first();

    //     if ($wallet) {
    //         $opening_balance = $wallet->main_balance;
    //         $closing_balance = $type === 'credit' ? $opening_balance + $amount : $opening_balance - $amount;

    //         return $wallet->update([
    //             'main_balance' => $closing_balance,
    //         ]);
    //     }

    //     return false;
    // }
}