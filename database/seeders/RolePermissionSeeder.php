<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $definitions = config('admin_permissions.permissions', []);

        foreach ($definitions as $slug => $definition) {
            Permission::updateOrCreate(
                ['slug' => $slug],
                [
                    'label' => $definition['label'] ?? $slug,
                    'group' => $definition['group'] ?? null,
                    'description' => $definition['description'] ?? null,
                ]
            );
        }

        $superAdmin = Role::updateOrCreate(
            ['slug' => 'super-admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Full access to all admin sections, user management, and role configuration.',
                'responsibilities' => 'Manage the entire admin portal, create users, define roles, and configure permissions for each menu section.',
                'is_super' => true,
            ]
        );

        Role::updateOrCreate(
            ['slug' => 'viewer'],
            [
                'name' => 'Viewer',
                'description' => 'Read-only access to selected submission sections.',
                'responsibilities' => 'Review submissions and monitor activity without making changes.',
                'is_super' => false,
            ]
        )->permissions()->sync(
            Permission::whereIn('slug', [
                'admin.overview',
                'submissions.feedback.view',
                'submissions.contact.view',
            ])->pluck('id')
        );

        User::query()->whereNull('role_id')->update(['role_id' => $superAdmin->id, 'is_active' => true]);
    }
}
