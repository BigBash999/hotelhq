<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash; // <--- Added this

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create an HR Manager
        $hrDept = Department::where('code', 'HR')->first();
        $hr = User::create([
            'name' => 'HR Manager',
            'email' => 'hr@hotelhq.test',
            'password' => Hash::make('password'),
            'employee_code' => 'HR-0001',
            'department_id' => $hrDept->id,
            'qr_token' => Str::uuid(),
        ]);
        $hr->assignRole('hr');

        // 2. Create HODs and Employees for all operational departments
        $depts = Department::where('is_operational', true)->get();
        
        foreach ($depts as $dept) {
            // Create 1 HOD
            $hod = User::create([
                'name' => 'HOD ' . $dept->name,
                'email' => 'hod.' . strtolower($dept->code) . '@hotelhq.test', // <--- Fixed quotes here
                'password' => Hash::make('password'),
                'employee_code' => $dept->code . '-0001',
                'department_id' => $dept->id,
                'qr_token' => Str::uuid(),
            ]);
            $hod->assignRole('hod');

            // Create 5 Employees
            for ($i = 2; $i <= 6; $i++) {
                $emp = User::create([
                    'name' => $dept->name . ' Employee ' . $i,
                    'email' => 'emp.' . strtolower($dept->code) . $i . '@hotelhq.test',
                    'password' => Hash::make('password'),
                    'employee_code' => $dept->code . '-000' . $i,
                    'department_id' => $dept->id,
                    'qr_token' => Str::uuid(),
                ]);
                $emp->assignRole('employee');
            }
        }
    }
}