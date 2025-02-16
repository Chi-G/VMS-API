<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Staff::create([
            'name' => 'John Sunday',
            'email' => 'john.sunday@sydani.com',
            'role' => 'manager',
            'department' => 'IT',
            'last_login' => now(),
            'status' => true,
            'position' => 'Senior Developer',
            'company_name' => 'Tech Solutions',
            'employee_id' => 'EMP12345',
            'profile_picture' => 'profile_pictures/john_doe.jpg',
            'password' => Hash::make('password'),
        ]);

        Staff::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@sydani.com',
            'role' => 'staff',
            'department' => 'HR',
            'last_login' => now(),
            'status' => true,
            'position' => 'HR Specialist',
            'company_name' => 'Tech Solutions',
            'employee_id' => 'EMP12346',
            'profile_picture' => 'profile_pictures/jane_smith.jpg',
            'password' => Hash::make('password'),
        ]);
    }
}
