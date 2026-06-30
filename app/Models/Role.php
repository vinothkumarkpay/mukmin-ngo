<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'responsibilities',
        'is_super',
    ];

    protected $casts = [
        'is_super' => 'boolean',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function hasPermission(string $slug): bool
    {
        if ($this->is_super) {
            return true;
        }

        return $this->permissions->contains('slug', $slug);
    }

    public function syncPermissionSlugs(array $slugs): void
    {
        if ($this->is_super) {
            return;
        }

        $permissionIds = Permission::whereIn('slug', $slugs)->pluck('id');
        $this->permissions()->sync($permissionIds);
    }
}
