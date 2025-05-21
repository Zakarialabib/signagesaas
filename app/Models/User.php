<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use BelongsToTenant;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'role',
        'preferences',
        'job_title',
        'timezone',
        'language',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'preferences'       => 'array',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** Check if the user is an admin */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    /**
     * Check if the user has a specific role
     * (either legacy 'role' property or Spatie role)
     */
    public function hasRole(string $roleName): bool
    {
        // Check if the user has the role as a property
        if ($this->role === $roleName) {
            return true;
        }

        // Check if the user has the role using Spatie's HasRoles trait
        return parent::hasRole($roleName);
    }

    /** Get user initials from name */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name));

        if (count($words) === 1) {
            return strtoupper(substr($words[0], 0, 1));
        }

        return strtoupper(substr($words[0], 0, 1).substr(end($words), 0, 1));
    }
}
