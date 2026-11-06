<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Roster;
use App\Models\LeaveRequest;

class AdminDashboardController extends Controller
{
        public function index()
    {
        // 1. High-level Stats
        $totalUsers = User::count();
        $totalDepartments = Department::count();
        $totalRosters = Roster::count();
        $totalLeaves = LeaveRequest::where('status', 'approved')->count();

        // 2. All Users
        $users = User::with(['department', 'roles'])->orderBy('name')->get();

        // 3. Fetch real Audit Logs (NEW)
        $auditLogs = \App\Models\AuditLog::with('actor')->orderBy('created_at', 'desc')->take(15)->get();

        return view('dashboards.admin', compact('totalUsers', 'totalDepartments', 'totalRosters', 'totalLeaves', 'users', 'auditLogs'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        // Sync the role (removes old roles, adds the new one)
        $user->syncRoles([$request->role]);

        return back()->with('success', 'Role updated successfully for ' . $user->name);
    }
        public function resetPassword(User $user)
    {
        // Reset the password to a default temporary one
        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make('password123')
        ]);

        return back()->with('success', 'Password for ' . $user->name . ' has been reset to: password123');
    }
}