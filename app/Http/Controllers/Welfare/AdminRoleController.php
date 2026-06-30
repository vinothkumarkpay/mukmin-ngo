<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Welfare\Concerns\ChecksAdminAccess;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminRoleController extends Controller
{
    use ChecksAdminAccess;

    public function index()
    {
        $this->authorizePermission('admin.roles.manage');

        $roles = Role::withCount('users')->orderBy('name')->get();

        return view('welfare.admin.roles.index', [
            'roles' => $roles,
            'activeTab' => 'panel-roles',
            'sidebarContext' => 'roles',
        ]);
    }

    public function create()
    {
        $this->authorizePermission('admin.roles.manage');

        return view('welfare.admin.roles.form', [
            'role' => new Role(),
            'permissionGroups' => $this->access()->permissionGroups(),
            'menuItems' => $this->access()->menus(),
            'selectedPermissions' => $this->access()->allPermissionSlugs(),
            'activeTab' => 'panel-roles',
            'sidebarContext' => 'roles',
            'formAction' => route('welfare.admin.roles.store'),
            'formMethod' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizePermission('admin.roles.manage');

        $data = $this->validateRole($request);

        $role = Role::create([
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name']),
            'description' => $data['description'] ?? null,
            'responsibilities' => $data['responsibilities'] ?? null,
            'is_super' => false,
        ]);

        $role->syncPermissionSlugs($data['permissions'] ?? []);

        return redirect()->route('welfare.admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $this->authorizePermission('admin.roles.manage');

        $role->load('permissions');

        return view('welfare.admin.roles.form', [
            'role' => $role,
            'permissionGroups' => $this->access()->permissionGroups(),
            'menuItems' => $this->access()->menus(),
            'selectedPermissions' => $role->is_super
                ? $this->access()->allPermissionSlugs()
                : $role->permissions->pluck('slug')->all(),
            'activeTab' => 'panel-roles',
            'sidebarContext' => 'roles',
            'formAction' => route('welfare.admin.roles.update', $role),
            'formMethod' => 'PUT',
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $this->authorizePermission('admin.roles.manage');

        if ($role->is_super) {
            return back()->with('error', 'The Super Admin role cannot be modified.');
        }

        $data = $this->validateRole($request, $role);

        $role->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'responsibilities' => $data['responsibilities'] ?? null,
        ]);

        $role->syncPermissionSlugs($data['permissions'] ?? []);

        return redirect()->route('welfare.admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $this->authorizePermission('admin.roles.manage');

        if ($role->is_super) {
            return back()->with('error', 'The Super Admin role cannot be deleted.');
        }

        if ($role->users()->exists()) {
            return back()->with('error', 'Cannot delete a role that is assigned to users.');
        }

        $role->delete();

        return redirect()->route('welfare.admin.roles.index')->with('success', 'Role deleted successfully.');
    }

    private function validateRole(Request $request, ?Role $role = null): array
    {
        $validSlugs = $this->access()->allPermissionSlugs();

        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role?->id),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'responsibilities' => ['nullable', 'string', 'max:5000'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($validSlugs)],
        ]);
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (Role::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
