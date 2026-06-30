@extends('welfare.layouts.admin')

@section('title', ($role->exists ? 'Edit Role' : 'Add Role') . ' - MUKMIN Admin')

@section('body')
<div class="dashboard-wrapper">
    @include('welfare.admin.partials.admin-sidebar', [
        'sidebarContext' => $sidebarContext ?? 'roles',
        'activeTab' => $activeTab ?? 'panel-roles',
    ])

    <main class="main-content">
        @include('welfare.admin.partials.admin-top-nav', ['pageTitle' => $role->exists ? 'Edit Role' : 'Add Role'])

        <div class="content-body">
            @if($errors->any())
                <div class="alert-admin alert-admin-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul style="margin: 8px 0 0 18px; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($role->is_super)
                <div class="alert-admin alert-admin-success">
                    <i class="fas fa-shield-alt"></i> Super Admin has full access to all sections and permissions. This role cannot be modified.
                </div>
            @endif

            <form method="POST" action="{{ $formAction }}">
                @csrf
                @if($formMethod !== 'POST')
                    @method($formMethod)
                @endif

                <div class="dashboard-card" style="margin-bottom: 24px;">
                    <div class="card-header">
                        <h3>Role Details</h3>
                    </div>
                    <div class="card-body admin-form-grid">
                        <div class="form-group">
                            <label for="name">Role Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}" required class="form-control" @if($role->is_super) readonly @endif>
                        </div>

                        <div class="form-group form-group-full">
                            <label for="description">Description</label>
                            <input type="text" id="description" name="description" value="{{ old('description', $role->description) }}" class="form-control" @if($role->is_super) readonly @endif>
                        </div>

                        <div class="form-group form-group-full">
                            <label for="responsibilities">Roles & Responsibilities</label>
                            <textarea id="responsibilities" name="responsibilities" rows="3" class="form-control" @if($role->is_super) readonly @endif>{{ old('responsibilities', $role->responsibilities) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card" style="margin-bottom: 24px;">
                    <div class="card-header">
                        <h3>Menu Access</h3>
                        <p style="margin: 8px 0 0; font-size: 13px; color: var(--admin-text-muted);">Select which sidebar sections this role can see. Each section requires its view permission.</p>
                    </div>
                    <div class="card-body">
                        <div class="permission-menu-grid">
                            @foreach($menuItems as $menuId => $menu)
                                @php
                                    $permSlug = $menu['permission'] ?? null;
                                    $checked = $permSlug && in_array($permSlug, old('permissions', $selectedPermissions), true);
                                @endphp
                                @if($permSlug)
                                    <label class="permission-menu-item">
                                        <input type="checkbox" name="permissions[]" value="{{ $permSlug }}" {{ ($role->is_super || $checked) ? 'checked' : '' }} @if($role->is_super) disabled @endif>
                                        <span>
                                            <i class="fas {{ $menu['icon'] }}"></i>
                                            <strong>{{ $menu['label'] }}</strong>
                                            <small>{{ $permSlug }}</small>
                                        </span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="dashboard-card" style="margin-bottom: 24px;">
                    <div class="card-header">
                        <h3>Detailed Permissions</h3>
                        <p style="margin: 8px 0 0; font-size: 13px; color: var(--admin-text-muted);">Configure export, import, status updates, and other actions per section.</p>
                    </div>
                    <div class="card-body">
                        @foreach($permissionGroups as $groupName => $permissions)
                            <div class="permission-group">
                                <h4>{{ $groupName }}</h4>
                                <div class="permission-check-grid">
                                    @foreach($permissions as $slug => $definition)
                                        <label class="permission-check-item">
                                            <input type="checkbox" name="permissions[]" value="{{ $slug }}" {{ ($role->is_super || in_array($slug, old('permissions', $selectedPermissions), true)) ? 'checked' : '' }} @if($role->is_super) disabled @endif>
                                            <span>
                                                <strong>{{ $definition['label'] ?? $slug }}</strong>
                                                @if(!empty($definition['description']))
                                                    <small>{{ $definition['description'] }}</small>
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('welfare.admin.roles.index') }}" class="btn-admin btn-admin-secondary">Cancel</a>
                    @if(! $role->is_super)
                        <button type="submit" class="btn-admin btn-admin-primary">
                            <i class="fas fa-save"></i> {{ $role->exists ? 'Update Role' : 'Create Role' }}
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
