<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Evaluation;

class EvaluationController extends Controller
{
    // Show the form to evaluate an employee
    public function create(User $user)
    {
        // Security: HOD can only evaluate their own department
        if (auth()->user()->department_id !== $user->department_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('evaluations.create', compact('user'));
    }

    // Save the evaluation
    public function store(Request $request, User $user)
    {
        if (auth()->user()->department_id !== $user->department_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'score' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'type' => 'required|in:performance,attendance',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $user->evaluations()->create([
            'type' => $request->type,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'score' => $request->score,
            'comment' => $request->comment,
            'given_by' => auth()->id(),
        ]);

        return redirect()->route('hod.dashboard')->with('success', 'Evaluation saved successfully!');
    }
}