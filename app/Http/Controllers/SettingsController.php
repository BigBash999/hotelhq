<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'hotel_name' => Setting::get('hotel_name', 'HotelHQ'),
            'max_weekly_hours' => Setting::get('max_weekly_hours', '45'),
            'late_grace_period' => Setting::get('late_grace_period', '15'),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hotel_name' => 'required|string|max:255',
            'max_weekly_hours' => 'required|integer|min:1|max:168',
            'late_grace_period' => 'required|integer|min:0|max:60',
        ]);

        Setting::updateOrCreate(['key' => 'hotel_name'], ['value' => $request->hotel_name]);
        Setting::updateOrCreate(['key' => 'max_weekly_hours'], ['value' => $request->max_weekly_hours]);
        Setting::updateOrCreate(['key' => 'late_grace_period'], ['value' => $request->late_grace_period]);

        return back()->with('success', 'System settings updated successfully!');
    }
}