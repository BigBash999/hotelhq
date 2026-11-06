<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\User;

class HrDashboardController extends Controller
{
        public function index()
    {
        $pendingLeaves = LeaveRequest::with('user')->where('status', 'pending')->orderBy('created_at', 'desc')->get();

        $recentLeaves = LeaveRequest::with('user')->where('status', '!=', 'pending')->orderBy('handled_at', 'desc')->take(10)->get();

        $totalEmployees = User::count();
        
        // Get departments and their user counts
        $departments = \App\Models\Department::withCount('users')->get();

        return view('dashboards.hr', compact('pendingLeaves', 'recentLeaves', 'totalEmployees', 'departments'));
    }
}