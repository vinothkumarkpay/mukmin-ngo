<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pertubuhan Gabungan MUKMIN Nasional')</title>

    <!-- Google Fonts - Roboto (matches theme settings) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="MUKMIN">
    <meta property="og:description" content="Driving community transformation through unity, leadership and collective action.">
    <meta property="og:image" content="{{ asset('android-chrome-512x512.png') }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="512">
    <meta property="og:image:height" content="512">

    <link rel="stylesheet" href="{{ asset('css/welfare.css') }}?v={{ filemtime(public_path('css/welfare.css')) }}">
    @stack('styles')
</head>
<body class="@yield('body-class', '')">

    <!-- TOP BAR -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-inner">
                <div class="top-bar-info">
                    <span class="top-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ config('welfare.address_short') }}
                    </span>
                    <span class="top-info-item">
                        <i class="fas fa-phone"></i>
                        {{ config('welfare.phone') }}
                    </span>
                    <span class="top-info-item">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:{{ config('welfare.email') }}">{{ config('welfare.email') }}</a>
                    </span>
                </div>
                <div class="top-bar-social">
                    <a href="https://x.com/Mukminmy" class="social-icon" title="X / Twitter" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                    <a href="https://web.facebook.com/profile.php?id=61590435118262" class="social-icon" title="Facebook" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/mukmin.malaysia" class="social-icon" title="Instagram" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/in/mukminofficial/" class="social-icon" title="LinkedIn" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://www.tiktok.com/@mukminnasional?is_from_webapp=1&sender_device=pc" class="social-icon" title="TikTok" target="_blank" rel="noopener noreferrer"><i class="fab fa-tiktok"></i></a>
                    <a href="https://youtube.com/@mukmin-i7l?si=ZDB9eyr679HET6Ew" class="social-icon" title="YouTube" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- HEADER -->
    <header class="site-header" id="site-header">
        <!-- Top Row -->
        <div class="header-top-row">
            <div class="container">
                <div class="header-top-inner">
                    <!-- Logo -->
                    <div class="site-logo">
                        <a href="{{ route('welfare.home') }}" style="display: flex; align-items: center; text-decoration: none;">
                            <img src="{{ asset('welfare/img/mukmin_logo.png') }}" alt="{{ config('welfare.name', 'MUKMIN') }}" style="height: 48px; width: auto; max-width: 100%; display: block;">
                        </a>
                    </div>

                    <!-- Top Row Actions -->
                    <div class="header-top-actions">
                        <!-- Search Box (inline expandable) -->
                        <div class="search-box-inline" id="search-box-inline">
                            <form action="#" method="GET" onsubmit="return false;">
                                <input type="text" name="q" placeholder="enter keywords" id="search-input-inline" value="{{ request('q') }}">
                                <button type="button" class="search-clear" id="search-clear-btn"><i class="fas fa-times"></i></button>
                            </form>
                        </div>
                        
                        <!-- Register As A Member Button -->
                        <a href="{{ route('welfare.serve') }}" class="btn-register-rounded">Register As A Member</a>
                        
                        <!-- Donate Button -->
                        <a href="{{ route('welfare.donate') }}" class="btn-donate-rounded">Donate Now!</a>
                        
                        <!-- Search Toggle Icon -->
                        <button class="search-icon-btn" id="search-icon-btn" title="Search">
                            <i class="fas fa-search"></i>
                        </button>
                        
                        <!-- Mobile menu toggle -->
                        <button type="button" class="mobile-menu-toggle" id="mobile-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="main-nav">
                            <i class="fas fa-bars" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Row (Nav Menu) -->
        <div class="header-bottom-row">
            <div class="container">
                <nav class="main-nav" id="main-nav">
                    <ul class="nav-menu">
                        @foreach(config('welfare.nav', []) as $item)
                            @php
                                $hasDropdown = isset($item['children']) && count($item['children']) > 0;
                                $isActive = request()->routeIs($item['route']) || 
                                            (request()->is('/') && $item['route'] == 'welfare.home') ||
                                            ($hasDropdown && collect($item['children'])->contains(function($child) {
                                                return request()->routeIs($child['route']);
                                            }));
                            @endphp
                            <li class="nav-item {{ $hasDropdown ? 'has-dropdown' : '' }} {{ $isActive ? 'active' : '' }}">
                                <a href="{{ route($item['route']) }}">
                                    {{ $item['label'] }}
                                    @if($hasDropdown) <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 5px;"></i> @endif
                                </a>
                                @if($hasDropdown)
                                    <ul class="dropdown-menu">
                                        @foreach($item['children'] as $child)
                                            @php
                                                $childUrl = route($child['route']);
                                                if (isset($child['hash'])) {
                                                    $childUrl .= '#' . $child['hash'];
                                                }
                                                $childActive = request()->routeIs($child['route']) && !isset($child['hash']);
                                            @endphp
                                            <li class="{{ $childActive ? 'active' : '' }}">
                                                <a href="{{ $childUrl }}">{{ $child['label'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </div>

        <button type="button" class="mobile-nav-backdrop" id="mobile-nav-backdrop" aria-label="Close menu" hidden></button>
    </header>

    <!-- MAIN CONTENT -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="site-footer">
        <div class="footer-bottom" style="padding: 25px 0; border-top: 1px solid rgba(255,255,255,0.08); font-size: 13.5px; color: rgba(255,255,255,0.7);">
            <div class="container">
                <div class="footer-content-inline" style="display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap; line-height: 1.6;">
                    <div class="footer-left" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                        <span>&copy; 2026 Pertubuhan Gabungan MUKMIN Nasional (PPM-019-10-15042026)</span>
                        <span style="color: rgba(255,255,255,0.25);">|</span>
                        <a href="#" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Integrity</a>
                        <span style="color: rgba(255,255,255,0.25);">|</span>
                        <a href="#" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Legal Disclaimer</a>
                    </div>
                    <div class="footer-right" style="display: flex; align-items: center; gap: 18px;">
                        <a href="https://x.com/Mukminmy" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s; font-size: 15px;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'" title="X / Twitter" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                        <a href="https://web.facebook.com/profile.php?id=61590435118262" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s; font-size: 15px;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'" title="Facebook" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/mukmin.malaysia" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s; font-size: 15px;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'" title="Instagram" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/in/mukminofficial/" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s; font-size: 15px;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'" title="LinkedIn" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.tiktok.com/@mukminnasional?is_from_webapp=1&sender_device=pc" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s; font-size: 15px;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'" title="TikTok" target="_blank" rel="noopener noreferrer"><i class="fab fa-tiktok"></i></a>
                        <a href="https://youtube.com/@mukmin-i7l?si=ZDB9eyr679HET6Ew" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s; font-size: 15px;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'" title="YouTube" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/welfare.js') }}"></script>
    @stack('scripts')
</body>
</html>
