@extends('welfare.layouts.admin')

@section('title', 'User Management - MUKMIN Admin')

@section('body')
<div class="dashboard-wrapper">
    @include('welfare.admin.partials.admin-sidebar', [
        'sidebarContext' => $sidebarContext ?? 'users',
        'activeTab' => $activeTab ?? 'panel-users',
    ])

    <main class="main-content">
        @include('welfare.admin.partials.admin-top-nav', ['pageTitle' => 'User Management'])

        <div class="content-body">
            @if(session('success'))
                <div class="alert-admin alert-admin-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert-admin alert-admin-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <div class="dashboard-card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3>Admin Users</h3>
                    <a href="{{ route('welfare.admin.users.create') }}" class="btn-admin btn-admin-primary">
                        <i class="fas fa-user-plus"></i> Add User
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Responsibilities</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->isSuperAdmin())
                                                <span class="badge-admin badge-admin-primary">Super Admin</span>
                                            @else
                                                {{ optional($user->role)->name ?? '—' }}
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($user->responsibilities ?: optional($user->role)->responsibilities, 80) ?: '—' }}</td>
                                        <td>
                                            @if($user->is_active)
                                                <span class="badge-admin badge-admin-success">Active</span>
                                            @else
                                                <span class="badge-admin badge-admin-muted">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('welfare.admin.users.edit', $user) }}" class="btn-admin btn-admin-secondary" style="padding: 6px 10px;">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            @if(! $user->is(auth()->user()))
                                                <form action="{{ route('welfare.admin.users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this user?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-admin btn-admin-danger" style="padding: 6px 10px;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 30px;">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
