<?php

namespace App\Http\Controllers;

use App\Models\Roster;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\ShiftExchange;

class DashboardController extends Controller
{
                public function employee()
    {
        $user = auth()->user();
        
        $qrCode = QrCode::size(200)->generate($user->qr_token);
        $evaluations = $user->evaluations()->orderBy('created_at', 'desc')->get();

        // Get upcoming shifts
        $myRosters = Roster::with('shift')->where('user_id', $user->id)->where('date', '>=', now()->toDateString())->get();

        // Get pending swaps
        $pendingExchangesForMe = ShiftExchange::with(['requester', 'requesterRoster.shift', 'targetRoster.shift'])
            ->where('target_id', $user->id)
            ->where('status', 'peer_pending')
            ->get();

        // Prepare Chart Data (Reverse chronological for the chart to go left-to-right)
        $chartEvaluations = $evaluations->reverse();
        $chartLabels = $chartEvaluations->map(function($eval) {
            return \Carbon\Carbon::parse($eval->period_start)->format('M j');
        })->values();
        $chartData = $chartEvaluations->pluck('score')->values();

        return view('dashboards.employee', compact('user', 'qrCode', 'evaluations', 'myRosters', 'pendingExchangesForMe', 'chartLabels', 'chartData'));
    }
}