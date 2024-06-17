<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\EventRegistrationRequest;
use App\Http\Requests\Api\V1\EventRegistrationVerifyRequest;
use App\Models\EventRegistration;
use App\Services\Api\V1\EventRegistrationService;
use App\Traits\Api\V1\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Event\Event;

class EventRegistrationController extends Controller
{
    use ApiResponseTrait;

    public function register (EventRegistrationRequest $request, EventRegistrationService $auth_service)
    {
        $_data = (Object) $request->validated();

        $request = $auth_service->register($_data);
        
        return $this->successResponse("Check your email for your otp.", 201);
    }

    public function verify (EventRegistrationVerifyRequest $request, EventRegistrationService $auth_service)
    {
        $_data = (Object) $request->validated();

        $request = $auth_service->verify($_data);
        
        if ($request) {
            return $this->successResponse("Registration successful.");
        } else{
            return $this->serverErrorResponse('Wrong code? Resend.');
        }
    }

    public function count ()
    {
        return $this->successResponse(["count" => EventRegistration::where('email_verified_at', "!=", null)->count()], "User Count");
    }

    public function resend (String $email, EventRegistrationService $auth_service)
    {
        $_data = (Object) array(
            "email" => $email,
        );

        $request = $auth_service->resend($_data);
        // return $request;
        if ($request) {
            return $this->successResponse("The otp has been resent.", 201);
        } else{
            return $this->serverErrorResponse('An error occurred.');
        }
    }
}
