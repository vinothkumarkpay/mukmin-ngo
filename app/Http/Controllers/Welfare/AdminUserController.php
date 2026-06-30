<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Welfare\Concerns\ChecksAdminAccess;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    use ChecksAdminAccess;

    public function index()
    {
        $this->authorizePermission('admin.users.manage');

        $users = User::with('role')->orderBy('name')->get();

        return view('welfare.admin.users.index', [
            'users' => $users,
            'activeTab' => 'panel-users',
            'sidebarContext' => 'users',
        ]);
    }

    public function create()
    {
        $this->authorizePermission('admin.users.manage');

        return view('welfare.admin.users.form', [
            'user' => new User(),
            'roles' => $this->access()->rolesForSelect(),
            'activeTab' => 'panel-users',
            'sidebarContext' => 'users',
            'formAction' => route('welfare.admin.users.store'),
            'formMethod' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizePermission('admin.users.manage');

        $data = $this->validateUser($request);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'is_active' => $request->boolean('is_active', true),
            'responsibilities' => $data['responsibilities'] ?? null,
        ]);

        return redirect()->route('welfare.admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $this->authorizePermission('admin.users.manage');

        return view('welfare.admin.users.form', [
            'user' => $user,
            'roles' => $this->access()->rolesForSelect(),
            'activeTab' => 'panel-users',
            'sidebarContext' => 'users',
            'formAction' => route('welfare.admin.users.update', $user),
            'formMethod' => 'PUT',
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorizePermission('admin.users.manage');

        if ($user->is($this->adminUser()) && ! $request->boolean('is_active')) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $data = $this->validateUser($request, $user);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role_id = $data['role_id'];
        $user->is_active = $request->boolean('is_active', true);
        $user->responsibilities = $data['responsibilities'] ?? null;

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('welfare.admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->authorizePermission('admin.users.manage');

        if ($user->is($this->adminUser())) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('welfare.admin.users.index')->with('success', 'User deleted successfully.');
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
            'responsibilities' => ['nullable', 'string', 'max:5000'],
        ]);
    }
}
