<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Welfare NGO - Nonprofit Organization')</title>

    <!-- Google Fonts - Roboto (matches theme settings) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/welfare.css') }}">
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
                        Brooklyn, NY 10036, United States
                    </span>
                    <span class="top-info-item">
                        <i class="fas fa-phone"></i>
                        1-800-123-1234
                    </span>
                    <span class="top-info-item">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:example@welfare_ngo.com">example@welfare_ngo.com</a>
                    </span>
                </div>
                <div class="top-bar-social">
                    <a href="#" class="social-icon" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon" title="Google+"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social-icon" title="Vimeo"><i class="fab fa-vimeo-v"></i></a>
                    <a href="#" class="social-icon" title="Skype"><i class="fab fa-skype"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- HEADER -->
    <header class="site-header" id="site-header">
        <div class="container">
            <div class="header-inner">
                <!-- Logo -->
                <div class="site-logo">
                    <a href="{{ url('/') }}">
                        <span class="logo-text">WELFARE</span>
                        <span class="logo-sub">NGO & Nonprofit Organization</span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="main-nav" id="main-nav">
                    <ul class="nav-menu">
                        <li class="nav-item @if(request()->is('/')) active @endif">
                            <a href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item has-dropdown">
                            <a href="#">About Us</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('/about') }}">About Us</a></li>
                                <li><a href="{{ url('/our-team') }}">Our Team</a></li>
                                <li><a href="{{ url('/volunteer') }}">Volunteer Engagement</a></li>
                                <li><a href="{{ url('/communications') }}">Communications</a></li>
                                <li><a href="{{ url('/services') }}">Services</a></li>
                            </ul>
                        </li>
                        <li class="nav-item has-dropdown">
                            <a href="#">Discover</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('/mission') }}">Mission</a></li>
                                <li><a href="{{ url('/where-we-are-headed') }}">Where We Are Headed</a></li>
                                <li><a href="{{ url('/history') }}">History</a></li>
                                <li><a href="{{ url('/board-and-staff') }}">Board and Staff</a></li>
                                <li><a href="{{ url('/join-our-team') }}">Join Our Team</a></li>
                            </ul>
                        </li>
                        <li class="nav-item has-dropdown">
                            <a href="#">Support</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('/terms') }}">Terms of Use</a></li>
                                <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
                                <li><a href="{{ url('/donor-privacy') }}">Donor Privacy Policy</a></li>
                                <li><a href="{{ url('/internship') }}">Internship</a></li>
                            </ul>
                        </li>
                        <li class="nav-item has-dropdown">
                            <a href="#">News</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('/press-room') }}">Press Room</a></li>
                                <li><a href="{{ url('/effectiveness') }}">Effectiveness & Results</a></li>
                                <li><a href="{{ url('/advisory-panel') }}">Advisory Panel</a></li>
                                <li><a href="{{ url('/endorsements') }}">Endorsements</a></li>
                                <li><a href="{{ url('/annual-report') }}">Annual Report</a></li>
                            </ul>
                        </li>
                        <li class="nav-item @if(request()->is('contact*')) active @endif">
                            <a href="{{ url('/contact') }}">Contact</a>
                        </li>
                    </ul>
                </nav>

                <!-- Header Actions -->
                <div class="header-actions">
                    <button class="search-toggle" id="search-toggle" title="Search">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ url('/donate') }}" class="btn-donate">Donate Now!</a>
                    <button class="mobile-menu-toggle" id="mobile-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="search-bar" id="search-bar">
            <div class="container">
                <form action="{{ url('/search') }}" method="GET" class="search-form">
                    <input type="text" name="q" placeholder="Search..." value="{{ request('q') }}">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="site-footer">
        <div style="display: none;" class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    <!-- About Widget -->
                    <div class="footer-widget">
                        <div class="footer-logo">
                            <span class="logo-text">WELFARE</span>
                        </div>
                        <p class="footer-about-text">We are a nonprofit organization dedicated to improving lives and creating lasting change in communities around the world.</p>
                        <div class="footer-social">
                            <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" title="Google+"><i class="fab fa-google-plus-g"></i></a>
                            <a href="#" title="Vimeo"><i class="fab fa-vimeo-v"></i></a>
                            <a href="#" title="Skype"><i class="fab fa-skype"></i></a>
                        </div>
                    </div>

                    <!-- About Us Links -->
                    <div class="footer-widget">
                        <h4 class="footer-widget-title">About Us</h4>
                        <ul class="footer-links">
                            <li><a href="{{ url('/about') }}">About Us</a></li>
                            <li><a href="{{ url('/our-team') }}">Our Team</a></li>
                            <li><a href="{{ url('/volunteer') }}">Volunteer Engagement</a></li>
                            <li><a href="{{ url('/communications') }}">Communications</a></li>
                            <li><a href="{{ url('/services') }}">Services</a></li>
                        </ul>
                    </div>

                    <!-- Discover Links -->
                    <div class="footer-widget">
                        <h4 class="footer-widget-title">Discover</h4>
                        <ul class="footer-links">
                            <li><a href="{{ url('/mission') }}">Mission</a></li>
                            <li><a href="{{ url('/where-we-are-headed') }}">Where We Are Headed</a></li>
                            <li><a href="{{ url('/history') }}">History</a></li>
                            <li><a href="{{ url('/board-and-staff') }}">Board and Staff</a></li>
                            <li><a href="{{ url('/join-our-team') }}">Join Our Team</a></li>
                        </ul>
                    </div>

                    <!-- News Links -->
                    <div class="footer-widget">
                        <h4 class="footer-widget-title">News</h4>
                        <ul class="footer-links">
                            <li><a href="{{ url('/press-room') }}">Press Room</a></li>
                            <li><a href="{{ url('/effectiveness') }}">Effectiveness & Results</a></li>
                            <li><a href="{{ url('/advisory-panel') }}">Advisory Panel</a></li>
                            <li><a href="{{ url('/endorsements') }}">Endorsements</a></li>
                            <li><a href="{{ url('/annual-report') }}">Annual Report</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <p class="footer-copyright">&copy; {{ date('Y') }} Welfare NGO. All Rights Reserved.</p>
                    <nav class="footer-nav">
                        <a href="{{ url('/terms') }}">Terms of Use</a>
                        <a href="{{ url('/privacy') }}">Privacy Policy</a>
                        <a href="{{ url('/contact') }}">Contact</a>
                    </nav>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/welfare.js') }}"></script>
    @stack('scripts')
</body>
</html>
