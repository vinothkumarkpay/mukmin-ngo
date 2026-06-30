@extends('welfare.layouts.admin')

@section('title', ($user->exists ? 'Edit User' : 'Add User') . ' - MUKMIN Admin')

@section('body')
<div class="dashboard-wrapper">
    @include('welfare.admin.partials.admin-sidebar', [
        'sidebarContext' => $sidebarContext ?? 'users',
        'activeTab' => $activeTab ?? 'panel-users',
    ])

    <main class="main-content">
        @include('welfare.admin.partials.admin-top-nav', ['pageTitle' => $user->exists ? 'Edit User' : 'Add User'])

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

            <div class="dashboard-card">
                <div class="card-header">
                    <h3>{{ $user->exists ? 'Edit User' : 'Create User' }}</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $formAction }}" class="admin-form-grid">
                        @csrf
                        @if($formMethod !== 'POST')
                            @method($formMethod)
                        @endif

                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="role_id">Role</label>
                            <select id="role_id" name="role_id" required class="form-control">
                                <option value="">Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}{{ $role->is_super ? ' (Super Admin)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="password">Password @if($user->exists)<small>(leave blank to keep current)</small>@endif</label>
                            <input type="password" id="password" name="password" class="form-control" @if(! $user->exists) required @endif>
                        </div>

                        @if(! $user->exists)
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                            </div>
                        @endif

                        <div class="form-group form-group-full">
                            <label for="responsibilities">Responsibilities <small>(optional override for this user)</small></label>
                            <textarea id="responsibilities" name="responsibilities" rows="4" class="form-control">{{ old('responsibilities', $user->responsibilities) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->exists ? $user->is_active : true) ? 'checked' : '' }}>
                                Account is active
                            </label>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('welfare.admin.users.index') }}" class="btn-admin btn-admin-secondary">Cancel</a>
                            <button type="submit" class="btn-admin btn-admin-primary">
                                <i class="fas fa-save"></i> {{ $user->exists ? 'Update User' : 'Create User' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
