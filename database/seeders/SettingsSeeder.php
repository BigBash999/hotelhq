<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(['key' => 'hotel_name'], ['value' => 'HotelHQ Management']);
        Setting::updateOrCreate(['key' => 'max_weekly_hours'], ['value' => '45']);
        Setting::updateOrCreate(['key' => 'late_grace_period'], ['value' => '15']); // minutes
    }
}