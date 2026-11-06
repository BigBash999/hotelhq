<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@hotelhq.test',
            'password' => Hash::make('ChangeMe123!'),
            'employee_code' => 'ADMIN-0001',
            'department_id' => null,
            'qr_token' => Str::uuid(),
            'is_active' => true,
        ]);

        $admin->assignRole('super_admin');
    }
}