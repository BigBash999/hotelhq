<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('shifts')->insert([
            ['name' => 'Morning', 'start_time' => '06:00', 'end_time' => '14:00', 'department_type' => 'operational', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Evening', 'start_time' => '14:00', 'end_time' => '22:00', 'department_type' => 'operational', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Office', 'start_time' => '08:00', 'end_time' => '17:00', 'department_type' => 'office', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}