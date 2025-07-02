<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::where('email', 'admin@admin.com')->first();
        if (is_null($user)) {
            $user = new User();
            $user->name = "Md. Ashrafur Rahman";
            $user->username = "superadmin";
            $user->email = "admin@admin.com";
            $user->password = Hash::make('Admin@123');
            $user->save();
        }
        $this->call(RolePermissionSeeder::class);
    }
}
