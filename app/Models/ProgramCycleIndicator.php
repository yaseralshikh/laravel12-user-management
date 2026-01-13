<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramCycleIndicator extends Model
{
    protected $fillable = [
        'program_cycle_id',
        'title',
        'target_value',
        'actual_value',
        'notes',
    ];
}
