<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    // This allows Laravel to save data to these specific columns
    protected $fillable = [
        'user_id',
        'type',
        'period_start',
        'period_end',
        'score',
        'comment',
        'given_by',
        'edited_at',
        'edit_count',
    ];
}