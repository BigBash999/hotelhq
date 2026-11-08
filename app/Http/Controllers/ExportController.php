<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function exportAttendance()
    {
        // Get attendance for the current month
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        $attendances = Attendance::with(['user', 'roster.shift'])
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'desc')
            ->get();

        $fileName = 'attendance_report_' . Carbon::now()->format('M_Y') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Employee Name', 'Employee Code', 'Date', 'Shift', 'Check-In Time', 'Status'];

        $callback = function() use($attendances, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->user->name,
                    $attendance->user->employee_code,
                    $attendance->date,
                    $attendance->roster->shift->name ?? 'N/A',
                    $attendance->check_in_at ? \Carbon\Carbon::parse($attendance->check_in_at)->format('h:i A') : 'N/A',
                    strtoupper($attendance->status),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}