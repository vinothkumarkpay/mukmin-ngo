@extends('welfare.layouts.admin')

@section('title', 'Roles & Permissions - MUKMIN Admin')

@section('body')
<div class="dashboard-wrapper">
    @include('welfare.admin.partials.admin-sidebar', [
        'sidebarContext' => $sidebarContext ?? 'roles',
        'activeTab' => $activeTab ?? 'panel-roles',
    ])

    <main class="main-content">
        @include('welfare.admin.partials.admin-top-nav', ['pageTitle' => 'Roles & Permissions'])

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
                    <h3>Roles</h3>
                    <a href="{{ route('welfare.admin.roles.create') }}" class="btn-admin btn-admin-primary">
                        <i class="fas fa-plus"></i> Add Role
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>Description</th>
                                    <th>Responsibilities</th>
                                    <th>Users</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td>
                                            {{ $role->name }}
                                            @if($role->is_super)
                                                <span class="badge-admin badge-admin-primary">Super</span>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($role->description, 100) ?: '—' }}</td>
                                        <td>{{ Str::limit($role->responsibilities, 100) ?: '—' }}</td>
                                        <td>{{ $role->users_count }}</td>
                                        <td>
                                            <a href="{{ route('welfare.admin.roles.edit', $role) }}" class="btn-admin btn-admin-secondary" style="padding: 6px 10px;">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            @if(! $role->is_super && $role->users_count === 0)
                                                <form action="{{ route('welfare.admin.roles.destroy', $role) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this role?')">
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
                                        <td colspan="5" style="text-align: center; padding: 30px;">No roles found.</td>
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
