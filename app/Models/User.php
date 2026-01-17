<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements LaratrustUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nastional_id',
        'email',
        'phone',
        'sector_id',  // 'القطاع التعليمي'
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nastional_id' => 'string',
            'sector_id' => 'integer',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function schoolsAsCoordinator()
    {
        return $this->hasMany(School::class, 'coordinator_id');
    }

    public function schoolsAsPrincipal()
    {
        return $this->hasMany(School::class, 'principal_id');
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    /**
     * Get all visits conducted by this user.
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Get all work events organized by this user.
     */
    public function workEvents()
    {
        return $this->hasMany(WorkEvent::class);
    }

    /**
     * Get all visit attachments uploaded by this user.
     */
    public function uploadedAttachments()
    {
        return $this->hasMany(VisitAttachment::class, 'uploaded_by');
    }

    /**
     * Get all schools where this user is coordinator.
     */
    public function coordinatedSchools()
    {
        return $this->hasMany(School::class, 'coordinator_id');
    }

    /**
     * Get all schools where this user is principal.
     */
    public function principalSchools()
    {
        return $this->hasMany(School::class, 'principal_id');
    }

    /**
     * Get all schools (both as coordinator and principal).
     */
    public function allAssociatedSchools()
    {
        return $this->schoolsAsCoordinator()
            ->union($this->schoolsAsPrincipal()->getQuery());
    }
}
