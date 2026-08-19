<?php

namespace Database\Seeders;

use App\Models\SocialMedia\FollowUp\FollowUpStatus;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class FollowUpStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('seeders/json/followupstatus.json'));
        $followupsArray = json_decode($json, true);
        $followups = $followupsArray[0]['data'];
        $user = User::where('email', 'hop@glendonedu.com')->first();
        foreach ($followups as $followup) {
            FollowUpStatus::create([
                "code" => $followup["code"],
                "name" => $followup["name"],
                "description" => $followup["description"],
                "color" => $followup["color"],
                "icon" => $followup["icon"],
                "is_completed" => $followup["is_completed"],
                "is_cancelled" => $followup["is_cancelled"],
                "is_default" => $followup["is_default"],
                "allow_reschedule" => $followup["allow_reschedule"],
                "allow_edit" => $followup["allow_edit"],
                "status" => $followup["status"],
                "sort_order" => $followup["sort_order"],
            ]);
        }
    }
}
