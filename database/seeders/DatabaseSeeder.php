<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            DepartmentSeeder::class,
            ShiftSeeder::class,
            SuperAdminSeeder::class,
            DemoDataSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}