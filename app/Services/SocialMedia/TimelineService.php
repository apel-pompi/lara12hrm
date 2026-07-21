<?php

namespace App\Services\SocialMedia;

use App\Models\SocialMedia\SocialMediaActivity;
use App\Models\SocialMedia\SocialMediaConversation;
use Illuminate\Support\Facades\Auth;

class TimelineService
{
    public function add(

        SocialMediaConversation $conversation,

        string $activity,

        string $title,

        ?string $description = null,

        array $meta = []

    ): SocialMediaActivity {

        return SocialMediaActivity::create([

            'conversation_id' => $conversation->id,

            'student_id' => optional(
                $conversation->contact
            )->student_id,

            'user_id' => Auth::user()->id,

            'activity' => $activity,

            'title' => $title,

            'description' => $description,

            'meta' => $meta,

        ]);
    }
}
