<?php

namespace App\Services\Api\V1;

use App\Mail\UserWelcomeEmail;
use App\Mail\UserVerifyEmail;
use App\Models\EventRegistration;
use App\Models\User;
use App\Services\Api\V1\WalletService;
use App\Traits\Api\V1\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EventRegistrationService
{
    use ApiResponseTrait;

    public function register (object $user_data)
    {
        $user = EventRegistration::create([
            'name' => $user_data->name,
            'email' => $user_data->email,
            'phone' => $user_data->phone,
        ]);
        
        $otp = $this->generateOtp($user->id);
        Mail::to($user->email)->send(new UserVerifyEmail($user->email, $user->name, $otp));
        return $user;
    }

    public function resend (object $user_data)
    {
        $user = EventRegistration::where('email', $user_data->email)->where('email_verified_at', null)->first();
        // return $user;
        if ($user !== null){
            $otp = $this->generateOtp($user->id);
    
            Mail::to($user->email)->send(new UserVerifyEmail($user->email, $user->name, $otp));
            return true;
        } else {
            return false;
        }
    }

    public function verify (object $user_data)
    {
        $instance = EventRegistration::where('email', $user_data->email)->first();
        if($user_data->otp == $instance->otp){
            $instance->update([
                'email_verified_at' => Carbon::now(),
                'otp_expires_at' => null,
                'otp' => null,
            ]);
            Mail::to($instance->email)->send(new UserWelcomeEmail($instance->email, $instance->name));
            return true;
        } else {
            return false;
            
        }        
    }

    protected function generateOtp ($user_id)
    {
        $instance = EventRegistration::where('id', $user_id)->first();

        $otp = random_int(100000, 999999);
        $time = Carbon::now();

        if($instance->otp !== '' && $instance->otp_expires_at!== '') {
            $instance->otp =  $otp;
            $instance->otp_expires_at = $time->addMinutes(30);
            $instance->save();
        } else {
            $instance->otp =  $otp;
            $instance->otp_expires_at = $time->addMinutes(30);
            $instance->save();
        }

        return $otp;
    }

    // private function createRefCode($string_length = 8, $recursion_limit = 10)
    // {
    //     if ($recursion_limit <= 0) {
    //         // We don't expect this to generate a code 10 times and all the codes are taken or for something to go wrong.
    //         // If such happens which is rare but not impossible, break out of the recursive loop.
    //         return null;
    //     }

    //     $randomString = Str::random($string_length);

    //     if (! $this->checkIfCodeExists($randomString)) {
    //         return $randomString;
    //     } else {
    //         return $this->createRefCode($string_length, $recursion_limit - 1);
    //     }
    // }

    // private function checkIfCodeExists(string $code)
    // {
    //     return User::where('ref_code', $code)->exists();
    // }   

   
}

