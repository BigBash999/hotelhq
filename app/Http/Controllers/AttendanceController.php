<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Roster;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Show the scanner page
    public function scanner()
    {
        return view('attendance.scanner');
    }

    // Process the scanned QR code
        public function scan(Request $request)
    {
        $request->validate(['qr_token' => 'required|string']);

        $user = User::where('qr_token', $request->qr_token)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid QR Code.']);
        }

        $today = Carbon::today()->format('Y-m-d');
        $roster = Roster::where('user_id', $user->id)->where('date', $today)->first();

        if (!$roster) {
            return response()->json(['success' => false, 'message' => $user->name . ' has no shift scheduled for today.']);
        }

        $existing = Attendance::where('user_id', $user->id)->where('date', $today)->first();
        $now = Carbon::now();

        // --- CLOCK OUT LOGIC ---
        if ($existing && !$existing->check_out_at) {
            $checkIn = Carbon::parse($existing->check_in_at);
            $totalMinutes = $checkIn->diffInMinutes($now);
            $totalHours = round($totalMinutes / 60, 2);

            $existing->update([
                'check_out_at' => $now,
                'total_hours' => $totalHours,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Goodbye! ' . $user->name . ' clocked OUT. Worked: ' . $totalHours . ' hrs.'
            ]);
        }

        // --- IF ALREADY CLOCKED OUT ---
        if ($existing && $existing->check_out_at) {
            return response()->json(['success' => false, 'message' => $user->name . ' has already completed their shift today.']);
        }

        // --- CLOCK IN LOGIC ---
        $shiftStartTime = $roster->shift->start_time;
        $gracePeriod = Carbon::parse($today . ' ' . $shiftStartTime)->addMinutes((int)\App\Models\Setting::get('late_grace_period', 15));

        $status = $now->greaterThan($gracePeriod) ? 'late' : 'present';

        Attendance::create([
            'user_id' => $user->id,
            'roster_id' => $roster->id,
            'date' => $today,
            'check_in_at' => $now,
            'status' => $status,
            'marked_via' => 'qr',
            'marked_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Welcome! ' . $user->name . ' clocked IN as ' . strtoupper($status) . '.'
        ]);
    }
}