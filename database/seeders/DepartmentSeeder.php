<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('departments')->insert([
            ['name' => 'Housekeeping', 'code' => 'HK', 'is_operational' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kitchen', 'code' => 'KIT', 'is_operational' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Food & Beverages', 'code' => 'FB', 'is_operational' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Front Office', 'code' => 'FO', 'is_operational' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Maintenance', 'code' => 'MNT', 'is_operational' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Human Resources', 'code' => 'HR', 'is_operational' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}