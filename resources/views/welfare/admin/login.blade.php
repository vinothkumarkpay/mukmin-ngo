@extends('welfare.layouts.admin')

@section('title', 'Admin Login - MUKMIN Portal')

@section('body')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>mukmin</h1>
            <p>Admin Portal Access</p>
        </div>

        @if(session('error'))
            <div class="alert-admin alert-admin-error" style="padding: 10px; margin-bottom: 20px; font-size: 13.5px;">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert-admin alert-admin-success" style="padding: 10px; margin-bottom: 20px; font-size: 13.5px;">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('welfare.admin.login.submit') }}">
            @csrf
            
            <div class="admin-input-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="admin-input" placeholder="admin@mukmin.com" value="{{ old('email', 'admin@mukmin.com') }}" required autofocus>
                @error('email')
                    <span style="color: #b91c1c; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="admin-input-group" style="margin-bottom: 25px;">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="admin-input" placeholder="••••••••" value="admin123" required>
            </div>

            <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; justify-content: center; padding: 12px; font-size: 14.5px;">
                <i class="fas fa-sign-in-alt"></i> Login to Portal
            </button>
        </form>

        <div style="margin-top: 25px; text-align: center;">
            <a href="{{ route('welfare.home') }}" style="font-size: 13px; color: var(--admin-text-muted); display: inline-flex; align-items: center; gap: 6px;">
                <i class="fas fa-arrow-left"></i> Back to Main Site
            </a>
        </div>
    </div>
</div>
@endsection
