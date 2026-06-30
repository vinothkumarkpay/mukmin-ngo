<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'responsibilities',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function isSuperAdmin(): bool
    {
        return (bool) optional($this->role)->is_super;
    }

    public function hasPermission(string $slug): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        $role = $this->relationLoaded('role')
            ? $this->role
            : $this->role()->with('permissions')->first();

        if (! $role) {
            return false;
        }

        if ($role->is_super) {
            return true;
        }

        if ($role->relationLoaded('permissions')) {
            return $role->permissions->contains('slug', $slug);
        }

        return $role->permissions()->where('slug', $slug)->exists();
    }

    public function displayRoleName(): string
    {
        if ($this->isSuperAdmin()) {
            return 'Super Admin';
        }

        return optional($this->role)->name ?? 'No Role';
    }
}
