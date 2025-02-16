<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'Create/Edit Staff Accounts',
            'Assign Roles',
            'Generate Visitor Reports',
            'View Visitor Data',
            'Blacklist Management',
            'VIP Guest Management',
            'Visitor Check-in/Check-out',
            'Tag Printing',
            'Visitor Traffic Reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
