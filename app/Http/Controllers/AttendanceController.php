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

        // Find the user by their unique QR token
        $user = User::where('qr_token', $request->qr_token)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid QR Code.']);
        }

        // Check if they are supposed to work today
        $today = Carbon::today()->format('Y-m-d');
        $roster = Roster::where('user_id', $user->id)->where('date', $today)->first();

        if (!$roster) {
            return response()->json(['success' => false, 'message' => $user->name . ' has no shift scheduled for today.']);
        }

        // Check if they already scanned in
        $existing = Attendance::where('user_id', $user->id)->where('date', $today)->first();
        if ($existing) {
            return response()->json(['success' => false, 'message' => $user->name . ' is already marked as ' . $existing->status . ' today.']);
        }

        // Determine if they are Present or Late
        // We will assume shift 1 is Morning (6 AM) and shift 2 is Evening (2 PM)
        $shiftStartTime = $roster->shift->start_time;
        $currentTime = Carbon::now();
        $gracePeriod = Carbon::parse($today . ' ' . $shiftStartTime)->addMinutes(15);

        $status = $currentTime->greaterThan($gracePeriod) ? 'late' : 'present';

        // Save the attendance
        Attendance::create([
            'user_id' => $user->id,
            'roster_id' => $roster->id,
            'date' => $today,
            'check_in_at' => $currentTime,
            'status' => $status,
            'marked_via' => 'qr',
            'marked_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Success! ' . $user->name . ' marked as ' . strtoupper($status) . '.'
        ]);
    }
}