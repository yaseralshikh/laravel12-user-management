<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Sector extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    /**
     * Get all schools in this sector.
     */
    public function schools(): HasMany
    {
        return $this->hasMany(School::class);
    }

    /**
     * Get all users in this sector.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all visits in schools of this sector.
     */
    public function visits(): HasManyThrough
    {
        return $this->hasManyThrough(
            Visit::class,
            School::class,
            'sector_id',  // Foreign key on schools table
            'school_id',  // Foreign key on visits table
            'id',         // Local key on sectors table
            'id'          // Local key on schools table
        );
    }

    /**
     * Get all program cycles through schools in this sector.
     */
    public function programCycles(): HasManyThrough
    {
        return $this->hasManyThrough(
            ProgramCycle::class,
            School::class,
            'sector_id',              // Foreign key on schools table
            'id',                     // Foreign key on program_cycles table (many-to-many via pivot)
            'id',                     // Local key on sectors table
            'id'                      // Local key on schools table
        );
    }
}
