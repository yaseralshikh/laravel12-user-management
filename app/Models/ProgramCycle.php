<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramCycle extends Model
{
    protected $fillable = [
        'program_id',
        'academic_year_id',
        'term',
        'status',
        'start_date',
        'end_date',
        'notes',
    ];
}
