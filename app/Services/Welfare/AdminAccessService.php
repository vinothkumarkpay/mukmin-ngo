<?php

namespace App\Services\Welfare;

use App\Models\User;
use Illuminate\Support\Collection;

class AdminAccessService
{
    /** @var array<string, array<string, mixed>>|null */
    private ?array $menus = null;

    /** @var array<string, array<string, mixed>>|null */
    private ?array $permissions = null;

    public function menus(): array
    {
        return $this->menus ??= config('admin_permissions.menus', []);
    }

    public function permissionDefinitions(): array
    {
        return $this->permissions ??= config('admin_permissions.permissions', []);
    }

    public function permissionGroups(): array
    {
        $groups = [];

        foreach ($this->permissionDefinitions() as $slug => $definition) {
            $group = $definition['group'] ?? 'Other';
            $groups[$group][$slug] = $definition;
        }

        return $groups;
    }

    public function menuItemsForUser(?User $user): array
    {
        $items = [];

        foreach ($this->menus() as $id => $menu) {
            $permission = $menu['permission'] ?? null;

            if ($permission && ! $this->userCan($user, $permission)) {
                continue;
            }

            $items[] = array_merge($menu, ['id' => $id]);
        }

        return $items;
    }

    public function userCan(?User $user, string $permission): bool
    {
        if (! $user) {
            return false;
        }

        return $user->hasPermission($permission);
    }

    public function submissionPermissionPrefix(string $type): ?string
    {
        return config("admin_permissions.submission_types.{$type}");
    }

    public function userCanSubmission(?User $user, string $type, string $action): bool
    {
        $prefix = $this->submissionPermissionPrefix($type);

        if (! $prefix) {
            return false;
        }

        return $this->userCan($user, "{$prefix}.{$action}");
    }

    public function allPermissionSlugs(): array
    {
        return array_keys($this->permissionDefinitions());
    }

    public function defaultFirstAccessibleTab(?User $user): string
    {
        $items = $this->menuItemsForUser($user);

        if (empty($items)) {
            return 'panel-overview';
        }

        $first = $items[0];

        return $first['id'] ?? 'panel-overview';
    }

    public function userCanAccessTab(?User $user, string $tabId): bool
    {
        $menu = $this->menus()[$tabId] ?? null;

        if (! $menu) {
            return false;
        }

        $permission = $menu['permission'] ?? null;

        if (! $permission) {
            return true;
        }

        return $this->userCan($user, $permission);
    }

    public function rolesForSelect(): Collection
    {
        return \App\Models\Role::orderBy('name')->get(['id', 'name', 'is_super']);
    }
}
