<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Security;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SecuritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Security::create([
            'name' => 'Julianne Smith',
            'email' => 'julianne.smith@sydani.com',
            'password' => Hash::make('password'),
            'position' => 'Security',
            'company_name' => 'Sydani Groups',
            'employee_id' => 'ST12345',
            'profile_picture' => 'profile_pictures/julianne_roberts.jpg',
        ]);

        Security::create([
            'name' => 'John Smith',
            'email' => 'john.smith@sydani.com',
            'password' => Hash::make('password'),
            'position' => 'Security',
            'company_name' => 'Sydani Groups',
            'employee_id' => 'ST12346',
            'profile_picture' => 'profile_pictures/john_smith.jpg',
        ]);
    }
}
