<?php

namespace Database\Seeders;

use App\Models\Default\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('seeders/json/states.json'));
        $statesArray = json_decode($json, true); 
        $states = $statesArray[0]['data'];
        foreach ($states as $state) {
            State::create([
                'name' => $state['name'],
                'country_id' => $state['country_id'],
                'latitude' => $state['latitude'],
                'longitude' => $state['longitude'],
                'status' => $state['status'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
