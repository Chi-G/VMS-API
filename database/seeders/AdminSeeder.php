<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'super_admin@sydani.com',
            'password' => Hash::make('password'),
            'role' => 'super-admin',
            'department' => 'Administration',
            'profile_picture' => 'profile_pictures/super_admin.jpg',
        ]);

        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@sydani.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department' => 'IT',
            'profile_picture' => 'profile_pictures/admin_user.jpg',
        ]);
    }
}
