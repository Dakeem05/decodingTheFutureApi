<?php

namespace App\Services\Api\V1;

use App\Models\Quest;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\UserQuest;

class QuestService
{

    public function create (object $data)
    {
        $quest = Quest::create([
            'name' => $data->name,
            'description' => $data->description,
            'point' => $data->point,
            'link' => $data->link,
            'requirement' => $data->requirement,
        ]);

        return $quest;
    }

    public function submit (object $data, int $user_id)
    {
        $instance = UserQuest::where('quest_id', $data->quest_id)->where('user_id', $user_id)->first();

        if ($instance == null) {
            $quest = Quest::find($data->quest_id);
            $userpoint = UserPoint::where('user_id', $user_id)->first();
            $userpoint->point = $userpoint->point + $quest->point;
            $userpoint->save();

            UserQuest::create([
                'user_id' => $user_id,
                'quest_id' => $data->quest_id,
                'proof' => $data->proof,
            ]);
            return true;
        } else {
            return false;
        }
    }
}

