<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    protected $fillable = [
        'name',
        'starts_on',
        'ends_on',
        'status',
    ];

    protected $casts = [
        'starts_on' => 'date:Y-m-d',
        'ends_on' => 'date:Y-m-d',
    ];

    /**
     * Get all program cycles for this academic year.
     */
    public function programCycles(): HasMany
    {
        return $this->hasMany(ProgramCycle::class);
    }

    /**
     * Get all program cycle indicators through program cycles.
     */
    public function programCycleIndicators()
    {
        return $this->hasManyThrough(
            ProgramCycleIndicator::class,
            ProgramCycle::class,
            'academic_year_id',
            'program_cycle_id'
        );
    }

    /**
     * Get all visits for this academic year.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Get all work events for this academic year.
     */
    public function workEvents(): HasMany
    {
        return $this->hasMany(WorkEvent::class);
    }
}
