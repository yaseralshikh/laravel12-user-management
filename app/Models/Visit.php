<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
        'academic_year_id',
        'program_cycle_id',
        'visit_date',
        'visit_type',
        'objective',
        'notes',
        'recommendations',
    ];
}
