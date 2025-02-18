<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AdminSeeder extends Seeder
{
    public function run()
    {
      
        Admin::updateOrCreate([
            'name' => 'Super Admin',
            'email' => 'super_admin@sydani.com',
            'password' => Hash::make('password'),
            'role' => 'super-admin',
            'department' => 'Administration',
            'profile_picture' => 'profile_pictures/super_admin.jpg',
        ]);

        Admin::updateOrCreate([
            'name' => 'Admin User',
            'email' => 'admin@sydani.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department' => 'IT',
            'profile_picture' => 'profile_pictures/admin_user.jpg',
        ]);
    }
}
