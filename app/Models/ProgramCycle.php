<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
    ];

    /**
     * Get the program that owns this cycle.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the academic year that owns this cycle.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get all indicators for this program cycle.
     */
    public function indicators(): HasMany
    {
        return $this->hasMany(ProgramCycleIndicator::class);
    }

    /**
     * Get all schools associated with this program cycle (Many-to-Many).
     */
    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'program_cycle_school')
            ->withTimestamps();
    }

    /**
     * Get all visits related to this program cycle.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Get all work events related to this program cycle.
     */
    public function workEvents(): HasMany
    {
        return $this->hasMany(WorkEvent::class);
    }
}
