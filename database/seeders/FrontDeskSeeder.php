<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FrontDeskUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FrontDeskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FrontDeskUser::create([
            'name' => 'Julianne Roberts Smith',
            'email' => 'julianne.roberts@sydani.com',
            'password' => Hash::make('password'),
            'position' => 'Front Desk',
            'company_name' => 'Sydani Groups',
            'employee_id' => 'FD12345',
            'profile_picture' => 'profile_pictures/julianne_roberts.jpg',
        ]);

        FrontDeskUser::create([
            'name' => 'Bob Smith',
            'email' => 'bob.smith@sydani.com',
            'password' => Hash::make('password'),
            'position' => 'Front Desk',
            'company_name' => 'Sydani Groups',
            'employee_id' => 'FD12346',
            'profile_picture' => 'profile_pictures/bob_smith.jpg',
        ]);
    }
}
