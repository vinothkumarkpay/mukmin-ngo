@php
    $sidebarContext = $sidebarContext ?? 'dashboard';
    $activeTab = $activeTab ?? 'panel-overview';
    $dashboardUrl = route('welfare.admin.dashboard');

    $navItems = [
        ['id' => 'panel-overview', 'icon' => 'fa-chart-pie', 'label' => 'Overview'],
        ['id' => 'panel-feedback', 'icon' => 'fa-comment-alt', 'label' => 'Feedback & Ideas'],
        ['id' => 'panel-ordinary', 'icon' => 'fa-building', 'label' => 'Ordinary Members'],
        ['id' => 'panel-friends', 'icon' => 'fa-users', 'label' => 'Friends of MUKMIN'],
        ['id' => 'panel-mentor', 'icon' => 'fa-user-tie', 'label' => 'Mentors'],
        ['id' => 'panel-partner', 'icon' => 'fa-handshake', 'label' => 'Partnerships'],
        ['id' => 'panel-volunteer', 'icon' => 'fa-hands-helping', 'label' => 'Volunteers'],
        ['id' => 'panel-aid', 'icon' => 'fa-hand-holding-medical', 'label' => 'Community Aid Requests'],
        ['id' => 'panel-mfls', 'icon' => 'fa-graduation-cap', 'label' => 'MFLS Scholarship Applications'],
        ['id' => 'panel-mfls-documents', 'icon' => 'fa-file-excel', 'label' => 'MFLS Partner Documents'],
        ['id' => 'panel-payments', 'icon' => 'fa-credit-card', 'label' => 'Donation Payments', 'route' => 'welfare.admin.donation-payments'],
        ['id' => 'panel-contact', 'icon' => 'fa-envelope', 'label' => 'Contact Messages'],
        ['id' => 'panel-options', 'icon' => 'fa-sliders-h', 'label' => 'Options Manager', 'divider' => true],
    ];
@endphp

<div class="sidebar-backdrop" id="sidebar-backdrop" aria-hidden="true"></div>

<aside class="sidebar" id="admin-sidebar">
    <div class="sidebar-header">
        <h1 class="sidebar-logo"><i class="fas fa-mosque"></i> mukmin <span>admin</span></h1>
    </div>
    <ul class="sidebar-menu">
        @foreach($navItems as $item)
            <li @if(!empty($item['divider'])) style="border-top: 1px solid rgba(255,255,255,0.08); margin-top: 15px; padding-top: 15px;" @endif>
                @php
                    $isActive = $activeTab === $item['id'];
                @endphp

                @if(!empty($item['route']))
                    <a href="{{ route($item['route']) }}" class="sidebar-link{{ $isActive ? ' active' : '' }}">
                        <i class="fas {{ $item['icon'] }}"></i> {{ $item['label'] }}
                    </a>
                @elseif($sidebarContext === 'dashboard')
                    <div class="sidebar-link{{ $isActive ? ' active' : '' }}" data-tab="{{ $item['id'] }}">
                        <i class="fas {{ $item['icon'] }}"></i> {{ $item['label'] }}
                    </div>
                @else
                    <a href="{{ $dashboardUrl }}#{{ $item['id'] }}" class="sidebar-link">
                        <i class="fas {{ $item['icon'] }}"></i> {{ $item['label'] }}
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
    <div class="sidebar-footer">
        <span>v1.0.0</span>
        <a href="{{ route('welfare.admin.logout') }}" class="logout-btn" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</aside>
