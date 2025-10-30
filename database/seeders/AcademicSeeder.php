<?php

namespace Database\Seeders;

use App\Models\AgencySetting\Academic;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class AcademicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('seeders/json/academic.json'));
        $feesArray = json_decode($json, true); 
        $fees = $feesArray[0]['data'];
        $user = User::where('email', 'hop@glendonedu.com')->first();
        foreach ($fees as $fee) {
            Academic::create([
                'name' => $fee['name'],
                'adddate' => date('Y-m-d'),
                'user_id' => $user->id,
                'active' => $fee['active'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
