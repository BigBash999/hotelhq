<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;

class LeaveController extends Controller
{
    // Employee: Submit a leave request
    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|in:annual,casual,sick,unpaid,maternity',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        auth()->user()->leaveRequests()->create([
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Leave request submitted successfully. Waiting for HR approval.');
    }

    // HR: Approve or Reject a leave request
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        // Security: Ensure only HR or Super Admin can do this
        if (!auth()->user()->hasAnyRole(['hr', 'super_admin'])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'hr_note' => 'nullable|string|max:1000',
        ]);

        $leaveRequest->update([
            'status' => $request->status,
            'hr_note' => $request->hr_note,
            'handled_by' => auth()->id(),
            'handled_at' => now(),
        ]);

        return back()->with('success', 'Leave request ' . $request->status . '.');
    }
}