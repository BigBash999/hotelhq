<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create the 4 roles
        Role::create(['name' => 'employee']);
        Role::create(['name' => 'hod']);
        Role::create(['name' => 'hr']);
        Role::create(['name' => 'super_admin']);
    }
}