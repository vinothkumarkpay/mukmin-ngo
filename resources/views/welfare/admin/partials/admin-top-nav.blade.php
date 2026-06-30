@php
    $user = auth()->user();
    $pageTitle = $pageTitle ?? 'Admin Panel';
@endphp
<header class="top-nav">
    <div class="top-nav-left">
        <button type="button" class="sidebar-toggle" id="sidebar-toggle" aria-label="Open navigation menu" aria-expanded="false" aria-controls="admin-sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <h2 id="top-nav-title">{{ $pageTitle }}</h2>
    </div>
    <div class="top-nav-actions" style="display: flex; align-items: center; gap: 20px;">
        <div class="user-profile">
            <i class="fas fa-user-shield"></i>
            <span>{{ $user->name }} <small style="opacity: 0.75;">({{ $user->displayRoleName() }})</small></span>
        </div>
        <a href="{{ route('welfare.admin.logout') }}" class="btn-admin btn-admin-secondary" style="padding: 8px 14px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;" title="Logout">
            <i class="fas fa-sign-out-alt"></i> <span class="btn-label">Logout</span>
        </a>
    </div>
</header>
