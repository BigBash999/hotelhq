<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    public function create()
    {
        $departments = Department::all();
        $allowedRoles = ['employee', 'hod'];
        if (auth()->user()->hasRole('super_admin')) {
            $allowedRoles[] = 'hr';
        }
        return view('admin.users.create', compact('departments', 'allowedRoles'));
    }

        public function store(Request $request)
    {
        // 1. Validate the request
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'department_id' => ['required', 'exists:departments,id'],
            'role' => ['required', 'string'],
        ]);

        // 2. Security Check
        if (auth()->user()->hasRole('hr') && !in_array($request->role, ['employee', 'hod'])) {
            abort(403, 'HR can only create Employees and HODs.');
        }

        // 3. Generate Employee Code
        $department = Department::find($request->department_id);
        $employeeCode = $department->code . '-' . rand(1000, 9999);

        // 4. Create the User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $request->department_id,
            'employee_code' => $employeeCode,
            'qr_token' => Str::uuid(),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // 5. Assign Role
        $user->assignRole($request->role);

        // 6. Redirect with explicit success message
        if (auth()->user()->hasRole('super_admin')) {
            return redirect()->route('admin.dashboard')->with('success', 'Account created for ' . $user->name);
        } else {
            return redirect()->route('hr.dashboard')->with('success', 'Account created for ' . $user->name);
        }
    }
}