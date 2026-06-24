<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //$user = User::where('email', 'reifatmia@yahoo.com')->first();
        // if (is_null($user)) {
        //     $user = new User();
        //     $user->name = "Md. Kawsar Ahmed";
        //     $user->username = "admin";
        //     $user->email = "reifatmia@yahoo.com";
        //     $user->password = Hash::make('Admin@123');
        //     $user->save();
        // }

        //$this->call(CompanySeeder::class);
        // $this->call(MasterSeeder::class);
        // $this->call(StudentSource::class);
        // $this->call(StudentStage::class);
        // $this->call(FeesTypeSeeder::class);
        // $this->call(InstallmentTypeSeeder::class);
        // $this->call(AcademicSeeder::class);
        // $this->call(TransactionSeeder::class);
        // $this->call(CountrySeeder::class);
        // $this->call(StatesSeeder::class);
        // $this->call(CitiesSeeder::class);
        $this->call(RolePermissionSeeder::class);
    }
}
