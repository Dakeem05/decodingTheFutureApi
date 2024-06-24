<?php

namespace App\Services\Api\V1;

use App\Models\User;
use App\Services\Api\V1\WalletService;
use App\Traits\Api\V1\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AuthenticationService
{
    use ApiResponseTrait;

    public function register (object $user_data)
    {
        $referral_code = $this->createRefCode();
        if ($referral_code == null) {
            return $this->serverErrorResponse('An error occured');
        }
        $user = User::create([
            'username' => isset($user_data->username) ? $user_data->username : null,
            'email' => $user_data->email,
            'password' => Hash::make($user_data->password),
            'name' => $user_data->name,
            'referrer_code' => isset($user_data->referral_code) ? $user_data->referral_code : null,
        ]);

        $this->createWallet($user->id);
        
        return $user;
    }



    private function createRefCode($string_length = 15, $recursion_limit = 10)
    {
        if ($recursion_limit <= 0) {
            // We don't expect this to generate a code 10 times and all the codes are taken or for something to go wrong.
            // If such happens which is rare but not impossible, break out of the recursive loop.
            return null;
        }

        $randomString = Str::random($string_length);

        if (! $this->checkIfCodeExists($randomString)) {
            return $randomString;
        } else {
            return $this->createRefCode($string_length, $recursion_limit - 1);
        }
    }

    private function checkIfCodeExists(string $code)
    {
        return User::where('ref_code', $code)->exists();
    }   

    public function createWallet($user_id)
    {
        $wallet_service = new WalletService();
        $wallet_service->createWallet($user_id);
    }
}

