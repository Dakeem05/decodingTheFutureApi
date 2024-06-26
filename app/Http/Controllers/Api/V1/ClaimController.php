<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\ClaimService;
use App\Traits\Api\V1\ApiResponseTrait;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    use ApiResponseTrait;

    public function claim(ClaimService $claimService)
    {
        $user_id = auth()->user()->id;
        $claim = $claimService->claim($user_id);
        if ($claim) {
            return $this->successResponse($claim, 'Claim successfully retrieved');
        } 
        return $this->errorResponse('Already claimed');
    }
}
