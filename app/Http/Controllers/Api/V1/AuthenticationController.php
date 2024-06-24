<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterUserRequest;
use App\Services\Api\V1\AuthenticationService;
use App\Traits\Api\V1\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Cast\Object_;

class AuthenticationController extends Controller
{
    use ApiResponseTrait;

    public function register (RegisterUserRequest $request, AuthenticationService $auth_service)
    {
        $_data = (Object) $request->validated();

        $request = $auth_service->register($_data);
        
        $token = Auth::login($request);
        return $this->successResponse([
            // "user" => $request,
            "token" => $token
        ], "Signup successfull", 201);
    }

    public function login (LoginRequest $request)
    {
        $credentials = $request->only(['username', 'password']);

        $token = Auth::attempt($credentials);

        if($token) {

            return $this->successResponse(['token' => $token], 'Login was successful.');
                    
        } else{
            return $this->unauthorizedResponse();
        }
    }

    public function getUser()
    {
        $user = Auth::user();

        if ($user) {
            return $this->successResponse(['user' => $user], 'User data', 200);
        } else {
            return $this->unauthorizedResponse();
            // return ApiResponse::errorResponse('invalid');
        }
    }

    public function logout(Request $request)
    {   
        Auth::logout(true);
        return $this->successResponse('Logged out');
    }
}
