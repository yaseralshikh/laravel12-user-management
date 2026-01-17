<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Get the user who conducted the visit.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the school being visited.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the academic year for this visit.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the program cycle for this visit.
     */
    public function programCycle(): BelongsTo
    {
        return $this->belongsTo(ProgramCycle::class);
    }

    /**
     * Get all attachments for this visit.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(VisitAttachment::class);
    }
}
