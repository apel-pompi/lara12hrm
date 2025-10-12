<?php

namespace Database\Seeders;

use App\Models\HRM\CompanyInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('seeders/json/company.json'));
        $companyArray = json_decode($json, true); 
        $company = $companyArray[0]['data'];
        foreach ($company as $companyes) {
            CompanyInfo::create([
                'companyname' => $companyes['companyname'],
                'address_one' => $companyes['address_one'],
                'address_two' => $companyes['address_two'],
                'company_phone' => $companyes['company_phone'],
                'company_phone' => $companyes['company_phone'],
                'companylogo' => $companyes['companylogo'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
