<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shift;
use App\Models\Roster;
use App\Models\Attendance;
use App\Models\ShiftExchange;
use Carbon\Carbon;

class HodDashboardController extends Controller
{
    public function index(Request $request)
    {
        $hod = auth()->user();
        
        // Determine which week to show (default to current week)
        $weekOffset = (int) $request->query('week', 0);
        $startOfWeek = Carbon::now()->startOfWeek()->addWeeks($weekOffset);
        
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfWeek->copy()->addDays($i);
        }

        // Load employees with their evaluations
        $employees = User::where('department_id', $hod->department_id)
            ->where('id', '!=', $hod->id)
            ->role('employee')
            ->with('evaluations')
            ->get();

        // Get shifts for this department
        $shifts = Shift::where('is_active', true)->get();

        // Get all rosters for this week
        $rosters = Roster::where('department_id', $hod->department_id)
            ->whereBetween('date', [$dates[0]->format('Y-m-d'), $dates[6]->format('Y-m-d')])
            ->get();

        // Calculate weekly hours and average rating for each employee
        $employees = $employees->map(function ($employee) use ($rosters, $shifts) {
            $employee->average_rating = $employee->evaluations->avg('score') ?? 0;
            
            $employeeRosters = $rosters->where('user_id', $employee->id);
            $totalMinutes = 0;
            
            foreach ($employeeRosters as $roster) {
                $shift = $shifts->find($roster->shift_id);
                if ($shift) {
                    $start = Carbon::parse($shift->start_time);
                    $end = Carbon::parse($shift->end_time);
                    $totalMinutes += $start->diffInMinutes($end);
                }
            }
            
            $employee->weekly_hours = round($totalMinutes / 60, 1);
            $employee->is_overtime = $employee->weekly_hours > (int)\App\Models\Setting::get('max_weekly_hours', 45);
            
            return $employee;
        });

        // Get shift exchanges waiting for HOD approval
        $pendingExchanges = ShiftExchange::with(['requester', 'target', 'requesterRoster.shift', 'targetRoster.shift'])
            ->where('status', 'hod_pending')
            ->whereHas('requesterRoster', function($query) use ($hod) {
                $query->where('department_id', $hod->department_id);
            })->get();

        // Fetch attendance records for this department for this week
        $attendanceLogs = Attendance::with(['user', 'roster.shift'])
            ->whereHas('user', function($query) use ($hod) {
                $query->where('department_id', $hod->department_id);
            })
            ->whereBetween('date', [$dates[0]->format('Y-m-d'), $dates[6]->format('Y-m-d')])
            ->orderBy('date', 'desc')
            ->orderBy('check_in_at', 'desc')
            ->get();

        return view('dashboards.hod', compact('employees', 'shifts', 'dates', 'rosters', 'pendingExchanges', 'attendanceLogs'));
    }

    public function assignShift(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'shift_id' => 'nullable', 
        ]);

        $hod = auth()->user();

        try {
            if ($request->shift_id === null) {
                Roster::where('user_id', $request->user_id)
                      ->where('date', $request->date)
                      ->delete();
                      
                \App\Helpers\AuditHelper::log('roster.removed', 'App\Models\Roster', $request->user_id, [
                    'date' => $request->date
                ]);
                
                return response()->json(['success' => true, 'message' => 'Assignment removed.']);
            }

            $roster = Roster::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'date' => $request->date,
                ],
                [
                    'shift_id' => $request->shift_id,
                    'department_id' => $hod->department_id,
                    'assigned_by' => $hod->id,
                ]
            );

            \App\Helpers\AuditHelper::log('roster.assigned', 'App\Models\Roster', $roster->id, [
                'date' => $request->date,
                'shift_id' => $request->shift_id,
                'user_id' => $request->user_id
            ]);

            return response()->json(['success' => true, 'message' => 'Assigned successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function toggleSpecialEvent(Request $request)
    {
        $request->validate(['date' => 'required|date']);
        $hod = auth()->user();

        $rosters = Roster::where('department_id', $hod->department_id)
            ->where('date', $request->date)
            ->get();

        if ($rosters->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Please assign at least one employee to this day first.']);
        }

        $newStatus = !$rosters->first()->is_special_event;
        
        Roster::where('department_id', $hod->department_id)
            ->where('date', $request->date)
            ->update(['is_special_event' => $newStatus]);

        return response()->json(['success' => true]);
    }
}