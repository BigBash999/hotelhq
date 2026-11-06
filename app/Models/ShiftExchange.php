<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftExchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_id',
        'target_id',
        'requester_roster_id',
        'target_roster_id',
        'reason',
        'peer_status',
        'peer_note',
        'peer_responded_at',
        'hod_status',
        'hod_note',
        'hod_approved_by',
        'hod_responded_at',
        'status',
    ];

    public function requester() { return $this->belongsTo(User::class, 'requester_id'); }
    public function target() { return $this->belongsTo(User::class, 'target_id'); }
    public function requesterRoster() { return $this->belongsTo(Roster::class, 'requester_roster_id'); }
    public function targetRoster() { return $this->belongsTo(Roster::class, 'target_roster_id'); }
}