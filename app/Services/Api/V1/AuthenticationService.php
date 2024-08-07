<?php

namespace App\Services\Api\V1;

use App\Mail\UserForgotPassword;
use App\Mail\UserVerifyEmail;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Models\UserPoint;
use App\Services\Api\V1\WalletService;
use App\Traits\Api\V1\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
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
            'referral_code' => $referral_code,
            'password' => Hash::make($user_data->password),
            'name' => $user_data->name,
            'referrer_code' => isset($user_data->referrer_code) ? $user_data->referrer_code : null,
        ]);

        $otp = PasswordResetToken::GenerateOtp($user->email);
        
        Mail::to($user->email)->send(new UserVerifyEmail($user->email, $user->name, $otp));
        
        return $user;
    }

    public function resend (object $user_data)
    {
        $user = User::where('email', $user_data->email)->where('email_verified_at', null)->first();
        
        if ($user !== null){
            $otp = PasswordResetToken::GenerateOtp($user->email);
            Mail::to($user->email)->send(new UserVerifyEmail($user->email, $user->name, $otp));
            return true;
        } else {
            return false;
        }
    }

    public function forgotPassword (object $user_data)
    {
        $user = User::where('email', $user_data->email)->first();
        
        if ($user !== null){
            $otp = PasswordResetToken::GenerateOtp($user->email);
            Mail::to($user->email)->send(new UserForgotPassword($user->email, $user->name, $otp));
            return true;
        } else {
            return false;
        }
    }

    public function verifyForgot (object $user_data)
    {
        $user = User::where('email' , $user_data->email)->first();
        $instance = PasswordResetToken::where('email', $user_data->email)->first();
        if ($instance !== null){
            if($user_data->otp == $instance->token){
                $instance->otp_verified_at = Carbon::now();
                $instance->save();
                
                return true;
            } else {
                return false;
            }    
        } else {
            return false;
        }
    }

    public function changePassword (object $user_data)
    {
        $user = User::where('email' , $user_data->email)->first();
        $instance = PasswordResetToken::where('email', $user_data->email)->first();
        if ($instance !== null){
            if ($instance->otp_verified_at !== null){
                $user->update([
                'password' => Hash::make($user_data->password),
            ]);
            $instance->delete();
            return true;
        } else {
            return false;
        }
        } else {
            return false;
        }
        
    }

    public function verify (object $user_data)
    {
        $user = User::where('email' , $user_data->email)->first();
        $instance = PasswordResetToken::where('email', $user_data->email)->first();
        if ($instance !== null){
            if($user_data->otp == $instance->token){
                $this->createWallet($user->id);
                $user->update(['email_verified_at' => Carbon::now()]);
                $instance->delete();

                // if($user->referrer_code == null){
                //     return true;
                // } else {
                //     $this->rewardReferrer($user->referrer_code);
                //     return true;
                // }
                
                return true;
            } else {
                return false;
            }    
        } else {
            return false;
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
        return User::where('referral_code', $code)->exists();
    }   

    public function createWallet($user_id)
    {
        $wallet_service = new WalletService();
        $wallet_service->createWallet($user_id);
    }
}

