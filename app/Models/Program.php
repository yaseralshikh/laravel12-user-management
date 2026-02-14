<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Program extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    /**
     * Get all program cycles for this program.
     */
    public function programCycles(): HasMany
    {
        return $this->hasMany(ProgramCycle::class);
    }

    public function visits()
    {
        return $this->belongsToMany(Visit::class, 'program_cycle_visit', 'program_cycle_id', 'visit_id')
            ->using(ProgramCycle::class);
    }

    /**
     * Get all indicators through program cycles.
     */
    public function indicators(): HasManyThrough
    {
        return $this->hasManyThrough(
            ProgramCycleIndicator::class,
            ProgramCycle::class,
            'program_id',      // Foreign key on program_cycles table
            'program_cycle_id' // Foreign key on program_cycle_indicators table
        );
    }

    /**
     * Get all work events through program cycles.
     */
    public function workEvents(): HasManyThrough
    {
        return $this->hasManyThrough(
            WorkEvent::class,
            ProgramCycle::class,
            'program_id',      // Foreign key on program_cycles table
            'program_cycle_id' // Foreign key on work_events table
        );
    }
}
