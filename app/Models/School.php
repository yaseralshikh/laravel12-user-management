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
        'educational_sector',
        'coordinator_id',
        'principal_id',
        
    ];

    protected $casts = [
        'stage' => 'string',
        'is_complex' => 'boolean',
    ];

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function principal()
    {
        return $this->belongsTo(User::class, 'principal_id');
    }
}
