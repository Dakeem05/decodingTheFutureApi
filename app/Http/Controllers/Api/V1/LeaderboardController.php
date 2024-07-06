<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\LeaderboardService;
use App\Traits\Api\V1\ApiResponseTrait;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    use ApiResponseTrait;
    
    public function index (LeaderboardService $leaderboardService)
    {
        $user_id = auth()->user()->id;
        $res = $leaderboardService->index($user_id);
        return $this->successResponse($res);
    }

    public function check (LeaderboardService $leaderboardService)
    {
        $request = $leaderboardService->check();
        
        if ($request == true) {
            return $this->successResponse("Points for referrals less than 20k receded successfully");
        } else {
            return $this->errorResponse("No referrals less than 20k.", 400);
        }
    }
}
