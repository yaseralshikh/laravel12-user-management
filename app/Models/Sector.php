<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function schools()
    {
        return $this->hasMany(School::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
