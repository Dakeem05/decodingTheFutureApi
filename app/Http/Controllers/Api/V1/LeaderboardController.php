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
}
