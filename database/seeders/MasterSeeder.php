<?php

namespace Database\Seeders;

use App\Models\AgencySetting\MasterCategory;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('seeders/json/master.json'));
        $countriesArray = json_decode($json, true); 
        $countries = $countriesArray[0]['data'];
        $user = User::where('email', 'hop@glendonedu.com')->first();
        foreach ($countries as $country) {
            MasterCategory::create([
                'catname' => $country['catname'],
                'catadddate' => date('Y-m-d'),
                'user_id' => $user->id,
                'active' => $country['active'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
