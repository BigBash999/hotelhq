<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'roster_id',
        'date',
        'check_in_at',
        'check_out_at', 
        'total_hours',
        'status',
        'marked_via',
        'marked_by',
        'note',
    ];

    // An attendance record belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // An attendance record belongs to a specific roster entry (shift)
    public function roster()
    {
        return $this->belongsTo(Roster::class);
    }
}