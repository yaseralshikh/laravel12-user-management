<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkEvent extends Model
{
    protected $fillable = [
        'user_id',
        'academic_year_id',
        'program_cycle_id',
        'event_date',
        'event_type',
        'title',
        'description',
        'location',
        'starts_at',
        'ends_at',
    ];
}
