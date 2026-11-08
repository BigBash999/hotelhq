<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HodDashboardController;
use App\Http\Controllers\HrDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ShiftExchangeController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('super_admin')) return redirect()->route('admin.dashboard');
    if ($user->hasRole('hr')) return redirect()->route('hr.dashboard');
    if ($user->hasRole('hod')) return redirect()->route('hod.dashboard');
    return redirect()->route('employee.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    
    // Super Admin
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->middleware('role:super_admin')->name('admin.dashboard');
    Route::patch('/admin/users/{user}/role', [AdminDashboardController::class, 'updateRole'])->middleware('role:super_admin')->name('admin.users.update-role');
    Route::patch('/admin/users/{user}/reset-password', [AdminDashboardController::class, 'resetPassword'])->middleware('role:super_admin')->name('admin.users.reset-password');

        // Settings
    Route::get('/admin/settings', [App\Http\Controllers\SettingsController::class, 'index'])->middleware('role:super_admin')->name('admin.settings');
    Route::post('/admin/settings', [App\Http\Controllers\SettingsController::class, 'update'])->middleware('role:super_admin')->name('admin.settings.update');
    // HR
    Route::get('/hr/dashboard', [HrDashboardController::class, 'index'])->middleware('role:hr')->name('hr.dashboard');

    // HOD
    Route::get('/hod/dashboard', [HodDashboardController::class, 'index'])->middleware('role:hod')->name('hod.dashboard');
    Route::post('/hod/roster/assign', [HodDashboardController::class, 'assignShift'])->middleware('role:hod')->name('hod.roster.assign');
    Route::post('/hod/roster/special-event', [HodDashboardController::class, 'toggleSpecialEvent'])->middleware('role:hod')->name('hod.roster.special-event');

    // Evaluations
    Route::get('/evaluations/create/{user}', [EvaluationController::class, 'create'])->middleware('role:hod')->name('evaluations.create');
    Route::post('/evaluations/store/{user}', [EvaluationController::class, 'store'])->middleware('role:hod')->name('evaluations.store');

    // Attendance
    Route::get('/attendance/scanner', [AttendanceController::class, 'scanner'])->middleware('role:hod')->name('attendance.scanner');
    Route::post('/attendance/scan', [AttendanceController::class, 'scan'])->middleware('role:hod')->name('attendance.scan');

    // Leaves
    Route::post('/leaves/store', [LeaveController::class, 'store'])->name('leaves.store');
    Route::patch('/leaves/{leaveRequest}/update', [LeaveController::class, 'update'])->middleware('role:hr|super_admin')->name('leaves.update');

    // Shift Exchanges
    Route::post('/shift-exchanges/store', [ShiftExchangeController::class, 'store'])->name('shift-exchanges.store');
    Route::patch('/shift-exchanges/{exchange}/peer', [ShiftExchangeController::class, 'peerRespond'])->name('shift-exchanges.peer');
    Route::patch('/shift-exchanges/{exchange}/hod', [ShiftExchangeController::class, 'hodApprove'])->middleware('role:hod')->name('shift-exchanges.hod');

    // User Management (Shared by HR & Admin)
    Route::get('/users/create', [UserManagementController::class, 'create'])->middleware('role:hr|super_admin')->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->middleware('role:hr|super_admin')->name('users.store');

    // Employee & Profile
    Route::get('/employee/dashboard', [DashboardController::class, 'employee'])->name('employee.dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Export CSV (HR & Admin)
    Route::get('/export/attendance', [App\Http\Controllers\ExportController::class, 'exportAttendance'])
        ->middleware('role:hr|super_admin')
        ->name('export.attendance');
    
        Route::post('/notifications/mark-read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.read');
});

require __DIR__.'/auth.php';