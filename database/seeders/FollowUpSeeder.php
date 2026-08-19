<?php

namespace Database\Seeders;

use App\Models\SocialMedia\FollowUp\FollowUpMaster;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class FollowUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('seeders/json/followup.json'));
        $followupsArray = json_decode($json, true);
        $followups = $followupsArray[0]['data'];
        $user = User::where('email', 'hop@glendonedu.com')->first();
        foreach ($followups as $followup) {
            FollowUpMaster::create([
                "code" => $followup["code"],
                "name" => $followup["name"],
                "description" => $followup["description"],
                "color" => $followup["color"],
                "icon" => $followup["icon"],
                "status" => $followup["status"],
                "sort_order" => $followup["sort_order"],
            ]);
        }
    }
}
