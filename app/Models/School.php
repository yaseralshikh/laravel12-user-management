<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    /** @use HasFactory<\Database\Factories\SchoolFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'ministry_code',
        'gender',
        'stage',
        'is_complex',
        'school_type',
        'building_type',
        'status',
        'sector_id',
        'coordinator_id',
        'principal_id',
    ];

    protected $casts = [
        'stage' => 'string',
        'sector_id' => 'integer',
        'is_complex' => 'boolean',
    ];

    /**
     * Get the coordinator user.
     */
    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    /**
     * Get the principal user.
     */
    public function principal()
    {
        return $this->belongsTo(User::class, 'principal_id');
    }

    /**
     * Get the sector that owns the school.
     */
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    /**
     * Get all program cycles associated with this school (Many-to-Many).
     */
    public function programCycles()
    {
        return $this->belongsToMany(ProgramCycle::class, 'program_cycle_school')
            ->withTimestamps();
    }

    /**
     * Get all visits to this school.
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
