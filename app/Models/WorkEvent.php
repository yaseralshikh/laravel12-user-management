<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * Get the user who organized this event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the academic year for this event.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the program cycle for this event.
     */
    public function programCycle(): BelongsTo
    {
        return $this->belongsTo(ProgramCycle::class);
    }
}
