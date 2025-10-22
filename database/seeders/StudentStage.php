<?php

namespace Database\Seeders;

use App\Models\Student\StudentStage as StudentStudentStage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\File;

class StudentStage extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('seeders/json/studentstage.json'));
        $feesArray = json_decode($json, true); 
        $fees = $feesArray[0]['data'];
        $user = User::where('email', 'admin@admin.com')->first();
        foreach ($fees as $fee) {
            StudentStudentStage::create([
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
