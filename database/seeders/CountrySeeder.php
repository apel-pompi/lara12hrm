<?php

namespace Database\Seeders;

use App\Models\Default\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('seeders/json/countries.json'));
        $countriesArray = json_decode($json, true); 
        $countries = $countriesArray[0]['data'];
        foreach ($countries as $country) {
            Country::create([
                'name' => $country['name'],
                'iso3' => $country['iso3'],
                'iso2' => $country['iso2'],
                'phonecode' => $country['phonecode'],
                'currency' => $country['currency'],
                'currency_symbol' => $country['currency_symbol'],
                'latitude' => $country['latitude'],
                'longitude' => $country['longitude'],
                'status' => $country['status'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
