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
     * Get all visits through program cycles.
     */
    public function visits(): HasManyThrough
    {
        return $this->hasManyThrough(
            Visit::class,
            ProgramCycle::class,
            'program_id',      // Foreign key on program_cycles table
            'program_cycle_id' // Foreign key on visits table
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
