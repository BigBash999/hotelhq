<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShiftExchange;
use App\Models\Roster;

class ShiftExchangeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'target_roster_id' => 'required|exists:rosters,id',
            'reason' => 'required|string|max:500',
        ]);

        $requesterRoster = Roster::where('user_id', auth()->id())
            ->where('date', $request->requester_date)
            ->firstOrFail();

        $targetRoster = Roster::findOrFail($request->target_roster_id);

        if ($requesterRoster->date !== $targetRoster->date) {
            return back()->with('error', 'Shifts must be on the same day.');
        }

        ShiftExchange::create([
            'requester_id' => auth()->id(),
            'target_id' => $targetRoster->user_id,
            'requester_roster_id' => $requesterRoster->id,
            'target_roster_id' => $targetRoster->id,
            'reason' => $request->reason,
            'status' => 'peer_pending',
        ]);

        return back()->with('success', 'Exchange request sent to your colleague.');
    }

    public function peerRespond(Request $request, ShiftExchange $exchange)
    {
        if (auth()->id() !== $exchange->target_id) abort(403);

        $request->validate(['peer_status' => 'required|in:accepted,declined']);

        $exchange->update([
            'peer_status' => $request->peer_status,
            'peer_responded_at' => now(),
            'status' => $request->peer_status === 'accepted' ? 'hod_pending' : 'rejected',
        ]);

        return back()->with('success', 'You have ' . $request->peer_status . ' the shift exchange.');
    }

    public function hodApprove(Request $request, ShiftExchange $exchange)
    {
        if (!auth()->user()->hasRole('hod')) abort(403);
        if ($exchange->status !== 'hod_pending') abort(400, 'Not ready for HOD approval.');

        $request->validate(['hod_status' => 'required|in:approved,rejected']);

        if ($request->hod_status === 'rejected') {
            $exchange->update(['hod_status' => 'rejected', 'status' => 'rejected', 'hod_note' => $request->hod_note]);
            return back()->with('success', 'Exchange rejected.');
        }

        // PERFORM THE ACTUAL SWAP
        $rosterA = $exchange->requesterRoster;
        $rosterB = $exchange->targetRoster;

        $userA_id = $rosterA->user_id;
        $userB_id = $rosterB->user_id;

        // 1. Save the data temporarily
        $dataA = [
            'date' => $rosterA->date,
            'shift_id' => $rosterA->shift_id,
            'department_id' => $rosterA->department_id,
            'assigned_by' => $rosterA->assigned_by,
        ];
        
        $dataB = [
            'date' => $rosterB->date,
            'shift_id' => $rosterB->shift_id,
            'department_id' => $rosterB->department_id,
            'assigned_by' => $rosterB->assigned_by,
        ];

        // 2. Delete the old rows to free up the unique constraint
        $rosterA->delete();
        $rosterB->delete();

        // 3. Recreate the rows with swapped users
        \App\Models\Roster::create(array_merge($dataA, ['user_id' => $userB_id]));
        \App\Models\Roster::create(array_merge($dataB, ['user_id' => $userA_id]));

        // 4. Mark exchange as approved
        $exchange->update([
            'hod_status' => 'approved',
            'status' => 'approved',
            'hod_approved_by' => auth()->id(),
            'hod_responded_at' => now(),
        ]);

        return back()->with('success', 'Exchange approved and shifts swapped!');
    }
}