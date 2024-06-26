<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\QuestCreationRequest;
use App\Http\Requests\Api\V1\QuestSubmissionRequest;
use App\Models\Quest;
use App\Models\User;
use App\Models\UserQuest;
use App\Services\Api\V1\QuestService;
use App\Traits\Api\V1\ApiResponseTrait;
use Illuminate\Http\Request;

class QuestController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quests = Quest::all();
        $user_id = auth()->user()->id;
        $data = [];
        foreach ($quests as $quest) {
            $quest_id = $quest->id;
            $instance = UserQuest::where('quest_id', $quest_id)->where('user_id', $user_id)->first();
            if ($instance == null) {
                $quest->is_complete = false;
            } else {
                $quest->is_complete = true;
            }
            
            $data[] = $quest;
        }
        return $this->successResponse($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestCreationRequest $request, QuestService $questService)
    {
        $_data = (Object) $request->validated();

        $request = $questService->create($_data);
        
        return $this->successResponse($request, "Created quests", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $quest_data = Quest::where('id',$id)->get();
        $data = [];
        foreach ($quest_data as $quest) {
            $quest_id = $quest->id;
            $instance = UserQuest::where('quest_id', $quest_id)->where('user_id', auth()->user()->id)->first();
            if ($instance == null) {
                $quest->is_complete = false;
            } else {
                $quest->is_complete = true;
            }
            
            $data[] = $quest;
        }
        return $this->successResponse($data);
    }

    public function submit(QuestSubmissionRequest $request, QuestService $questService)
    {
        $_data = (Object) $request->validated();

        $request = $questService->submit($_data, auth()->user()->id);
        
        if ($request) {
            return $this->successResponse("Quest successfully submitted", 201);
        } else {
            return $this->errorResponse("Quest already submitted", 400);
        }
    }

    public function submissions ()
    {
        $user_quest = UserQuest::all();
        $data = [];
        foreach ($user_quest as $quest) {
            $quest_data = Quest::where('id', $quest->quest_id)->get();
            $user = User::where('id', $quest->user_id)->get();
            $quest->user = $user;
            $quest->quest = $quest_data;
            $data[] = $quest;
        }
        return $this->successResponse($data);
    }

    public function submissionsSearch (string $proof)
    {
        $user_quest = UserQuest::where('proof', 'like', '%'. $proof. '%')->get();
        if (count($user_quest) == 0) {
            return $this->errorResponse("No quests found", 404);
        }
        $data = [];
        foreach ($user_quest as $quest) {
            $quest_data = Quest::where('id', $quest->quest_id)->get();
            $user = User::where('id', $quest->user_id)->get();
            $quest->user = $user;
            $quest->quest = $quest_data;
            $data[] = $quest;
        }
        return $this->successResponse($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
