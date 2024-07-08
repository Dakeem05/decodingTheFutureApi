<?php

namespace App\Services\Api\V1;

use App\Models\EventRegistration;
use App\Models\Quest;
use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Support\Facades\DB;
use App\Models\UserQuest;
use Carbon\Carbon;

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

    // public function submit (object $data, int $user_id)
    // {
    //     $instance = UserQuest::where('quest_id', $data->quest_id)->where('user_id', $user_id)->first();

    //     if ($instance == null) {
    //         $quest = Quest::find($data->quest_id);
    //         if ($quest->id == 2) {
    //             $user = User::find($user_id);
    //             $has_done = EventRegistration::where('email', $user->email)->exists();

    //             if ($has_done) {
    //                 $userpoint = UserPoint::where('user_id', $user_id)->first();
    //                 $userpoint->point = $userpoint->point + $quest->point;
    //                 $userpoint->save();

    //                 UserQuest::create([
    //                     'user_id' => $user_id,
    //                     'quest_id' => $data->quest_id,
    //                     'proof' => $data->proof,
    //                 ]);
    //                 return true;
    //             }
    //             return 'undone';
    //         }
    //         $userpoint = UserPoint::where('user_id', $user_id)->first();
    //         $userpoint->point = $userpoint->point + $quest->point;
    //         $userpoint->save();

    //         UserQuest::create([
    //             'user_id' => $user_id,
    //             'quest_id' => $data->quest_id,
    //             'proof' => $data->proof,
    //         ]);
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function submit(object $data, int $user_id)
{
    // Start a database transaction to ensure data consistency
    DB::beginTransaction();

    try {
        // Check if the user has already completed the quest
        $instance = UserQuest::where('quest_id', $data->quest_id)
            ->where('user_id', $user_id)
            ->first();

        if ($instance === null) {
            // Find the quest
            $quest = Quest::findOrFail($data->quest_id);

            $user = User::findOrFail($user_id);
            // Special handling for quest with ID 2
            // if ($quest->id == 2) {
            //     // Find the user
            //     $user = User::findOrFail($user_id);

            //     // Check if the user has done the specific event registration
            //     $has_done = EventRegistration::where('email', $user->email)->exists();

            //     if (!$has_done) {
            //         // Rollback transaction and return 'undone' if the user hasn't done the event registration
            //         DB::rollBack();
            //         return 'undone';
            //     }
            // }

            // Find or create the UserPoint record
            $userpoint = UserPoint::firstOrCreate(['user_id' => $user_id]);

            // Update user points
            $userpoint->increment('point', $quest->point);
            
            // Create the UserQuest record
            UserQuest::create([
                'user_id' => $user_id,
                'quest_id' => $data->quest_id,
                'proof' => $data->proof,
            ]);
            if ($userpoint->point >= 40000) {
                if($user->referrer_code !== null){
                    if($user->referral_counted_at !== null){
                        $this->rewardReferrer($user->referrer_code);
                        $userpoint->update([
                            'referral_counted_at' => Carbon::now()
                        ]);
                        // return true;
                    }
                }
            }
            // Commit the transaction
            DB::commit();
            return true;
        } else {
            // If the quest is already completed, rollback transaction and return false
            DB::rollBack();
            return false;
        }
    } catch (\Exception $e) {
        // Rollback the transaction in case of any error
        DB::rollBack();
        throw $e; // Optionally, log the error or handle it as needed
    }
}

    // public function eventRegistration ()
    // {
    //     $instance = UserQuest::where('quest_id', 2)->get();
    //     $quest = Quest::find(2);
    //     $response = false;
    //     foreach ($instance as $key => $value) {
    //         $user = User::find($value->user_id);
    //         $has_done = EventRegistration::where('email', $user->email)->exists();
    //         if (!$has_done) {   
    //             $userpoint = UserPoint::where('user_id', $value->user_id)->first();
    //             $userpoint->point = $userpoint->point - $quest->point;
    //             $userpoint->save();
    //             $value->delete();
    //             $response = true;
    //         }
    //     }

    //     return $response;
    // }
    
    public function eventRegistration()
{
    // Start a database transaction to ensure data consistency
    DB::beginTransaction();

    try {
        // Fetch all UserQuests with quest_id 2
        $instances = UserQuest::where('quest_id', 2)->get();
        $quest = Quest::findOrFail(2);
        $response = false;

        foreach ($instances as $instance) {
            // Find the user associated with the UserQuest
            $user = User::findOrFail($instance->user_id);

            // Check if the user has completed the event registration
            $has_done = EventRegistration::where('email', $user->email)->exists();

            if (!$has_done) {
                // Find the user's points record
                $userpoint = UserPoint::where('user_id', $instance->user_id)->first();

                if ($userpoint) {
                    // Deduct the quest points from the user's points
                    $userpoint->decrement('point', $quest->point);

                    // Delete the UserQuest record
                    $instance->delete();
                    $response = true;
                }
            }
        }

        // Commit the transaction
        DB::commit();
        return $response;
    } catch (\Exception $e) {
        // Rollback the transaction in case of any error
        DB::rollBack();
        throw $e; // Optionally, log the error or handle it as needed
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
}

