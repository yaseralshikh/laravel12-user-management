<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramCycleIndicator extends Model
{
    protected $fillable = [
        'program_cycle_id',
        'title',
        'target_value',
        'actual_value',
        'notes',
    ];

    /**
     * Get the program cycle that owns this indicator.
     */
    public function programCycle(): BelongsTo
    {
        return $this->belongsTo(ProgramCycle::class);
    }

    /**
     * Get the program through the program cycle.
     */
    public function program()
    {
        return $this->belongsToThrough(
            Program::class,
            ProgramCycle::class
        );
    }
}
