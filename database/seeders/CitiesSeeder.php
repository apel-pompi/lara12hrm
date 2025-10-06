<?php

namespace Database\Seeders;

use App\Models\Default\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('seeders/json/cities.json'));
        $cityArray = json_decode($json, true); 
        $city = $cityArray[0]['data'];
        foreach ($city as $cities) {
            City::create([
                'name' => $cities['name'],
                'state_id' => $cities['state_id'],
                'country_id' => $cities['country_id'],
                'latitude' => $cities['latitude'],
                'longitude' => $cities['longitude'],
                'status' => $cities['status'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
