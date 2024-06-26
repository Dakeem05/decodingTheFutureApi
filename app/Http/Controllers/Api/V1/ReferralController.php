<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\ReferralService;
use App\Traits\Api\V1\ApiResponseTrait;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    use ApiResponseTrait;
    public function index (ReferralService $referralService)
    {
        $user_id = auth()->user()->id;
        $referrals = $referralService->index($user_id);
        return $this->successResponse($referrals);
    }
}
