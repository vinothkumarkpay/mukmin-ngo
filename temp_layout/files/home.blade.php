@extends('layouts.app')

@section('title', 'Welfare NGO - Nonprofit Organization Charity Theme')

@section('body-class', 'home-page')

@section('content')

    <!-- HERO SLIDER SECTION -->
    <section class="hero-slider">
        <div class="slider-container">
            <!-- Slide 1 -->
            <div class="slide active" style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=1600');">
                <div class="slide-content">
                    <div class="container">
                        <div class="slide-text">
                            <h1>Together We Can<br>Change The World</h1>
                            <p>Join us in making a difference. Your support helps thousands of families every year access clean water, education, and healthcare.</p>
                            <div class="slide-buttons">
                                <a href="{{ url('/about') }}" class="btn btn-primary">Learn More</a>
                                <a href="{{ url('/donate') }}" class="btn btn-outline">Donate Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="slide" style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?w=1600');">
                <div class="slide-content">
                    <div class="container">
                        <div class="slide-text">
                            <h1>Your Donation<br>Changes Lives</h1>
                            <p>Every dollar you give goes directly toward our programs — helping children, families, and entire communities thrive.</p>
                            <div class="slide-buttons">
                                <a href="{{ url('/campaigns') }}" class="btn btn-primary">Our Campaigns</a>
                                <a href="{{ url('/donate') }}" class="btn btn-outline">Donate Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="slide" style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?w=1600');">
                <div class="slide-content">
                    <div class="container">
                        <div class="slide-text">
                            <h1>Building Hope<br>For Tomorrow</h1>
                            <p>Our volunteers and staff work tirelessly in communities around the globe. You can help us expand our impact.</p>
                            <div class="slide-buttons">
                                <a href="{{ url('/volunteer') }}" class="btn btn-primary">Get Involved</a>
                                <a href="{{ url('/donate') }}" class="btn btn-outline">Donate Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slider Controls -->
            <button class="slider-prev" id="slider-prev"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-next" id="slider-next"><i class="fas fa-chevron-right"></i></button>
            <div class="slider-dots" id="slider-dots">
                <span class="dot active" data-index="0"></span>
                <span class="dot" data-index="1"></span>
                <span class="dot" data-index="2"></span>
            </div>
        </div>
    </section>

    <!-- WELCOME / INTRO SECTION -->
    <section class="section-welcome bg-white">
        <div class="container">
            <div class="section-header text-center">
                <h2>Welcome to Welfare</h2>
                <div class="section-divider"><span></span></div>
                <p class="section-subtitle">We are a nonprofit organization committed to improving lives through sustainable programs and community partnerships worldwide.</p>
            </div>
            <div class="welcome-grid">
                <div class="welcome-card">
                    <div class="welcome-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p>To empower individuals and communities by providing access to essential resources, education, and healthcare regardless of background or circumstance.</p>
                    <a href="{{ url('/mission') }}" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="welcome-card">
                    <div class="welcome-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p>A world where every person has the opportunity to live a healthy, dignified, and fulfilling life, supported by thriving communities.</p>
                    <a href="{{ url('/about') }}" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="welcome-card">
                    <div class="welcome-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Our Values</h3>
                    <p>Compassion, integrity, transparency, and accountability guide everything we do — from how we raise funds to how we deliver programs.</p>
                    <a href="{{ url('/about') }}" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS / IMPACT SECTION (dark bg - matches theme's "third" color scheme #3d3d3d) -->
    <section class="section-stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" data-count="12500">0</div>
                    <div class="stat-label">People Helped</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="48">0</div>
                    <div class="stat-label">Countries Reached</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="320">0</div>
                    <div class="stat-label">Volunteers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="95">0</div>
                    <div class="stat-label">Programs Running</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CAMPAIGNS SECTION -->
    <section class="section-campaigns bg-light">
        <div class="container">
            <div class="section-header text-center">
                <h2>Our Campaigns</h2>
                <div class="section-divider"><span></span></div>
                <p class="section-subtitle">Your donations directly fund these life-changing programs around the world.</p>
            </div>
            <div class="campaigns-grid">
                @php
                $campaigns = [
                    ['title'=>'Clean Water Initiative','img'=>'https://images.unsplash.com/photo-1541544537156-7627a7a4aa1c?w=600','raised'=>18500,'goal'=>25000,'desc'=>'Bringing safe drinking water to rural communities in sub-Saharan Africa.'],
                    ['title'=>'Education for All','img'=>'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600','raised'=>32000,'goal'=>40000,'desc'=>'Building schools and providing scholarships to children in need.'],
                    ['title'=>'Healthcare Access','img'=>'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=600','raised'=>9800,'goal'=>20000,'desc'=>'Mobile clinics delivering essential healthcare to remote villages.'],
                ];
                @endphp

                @foreach($campaigns as $campaign)
                <div class="campaign-card">
                    <div class="campaign-img">
                        <img src="{{ $campaign['img'] }}" alt="{{ $campaign['title'] }}">
                        <div class="campaign-overlay">
                            <a href="{{ url('/campaigns') }}" class="btn btn-sm btn-primary">Learn More</a>
                        </div>
                    </div>
                    <div class="campaign-body">
                        <h3><a href="{{ url('/campaigns') }}">{{ $campaign['title'] }}</a></h3>
                        <p>{{ $campaign['desc'] }}</p>
                        <div class="campaign-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ round($campaign['raised']/$campaign['goal']*100) }}%"></div>
                            </div>
                            <div class="progress-meta">
                                <span class="raised">Raised: ${{ number_format($campaign['raised']) }}</span>
                                <span class="goal">Goal: ${{ number_format($campaign['goal']) }}</span>
                            </div>
                        </div>
                        <a href="{{ url('/donate') }}" class="btn btn-primary btn-full">Donate Now</a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-40">
                <a href="{{ url('/campaigns') }}" class="btn btn-outline-dark">View All Campaigns</a>
            </div>
        </div>
    </section>

    <!-- DONATE CTA SECTION (red accent - matches #d43c18) -->
    <section class="section-donate-cta">
        <div class="container">
            <div class="donate-cta-inner">
                <div class="donate-cta-text">
                    <h2>Make a Difference Today</h2>
                    <p>Your generosity helps us reach more communities and create lasting change. Every dollar matters.</p>
                </div>
                <div class="donate-cta-amounts">
                    <a href="{{ url('/donate?amount=25') }}" class="amount-btn">$25</a>
                    <a href="{{ url('/donate?amount=50') }}" class="amount-btn">$50</a>
                    <a href="{{ url('/donate?amount=100') }}" class="amount-btn">$100</a>
                    <a href="{{ url('/donate?amount=250') }}" class="amount-btn">$250</a>
                    <a href="{{ url('/donate') }}" class="btn btn-white">Other Amount</a>
                </div>
            </div>
        </div>
    </section>

    <!-- TEAM SECTION -->
    <section class="section-team bg-white">
        <div class="container">
            <div class="section-header text-center">
                <h2>Our Team</h2>
                <div class="section-divider"><span></span></div>
                <p class="section-subtitle">Dedicated professionals and volunteers working together to make a difference.</p>
            </div>
            <div class="team-grid">
                @php
                $team = [
                    ['name'=>'Sarah Johnson','role'=>'Executive Director','img'=>'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=300'],
                    ['name'=>'Michael Chen','role'=>'Program Director','img'=>'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=300'],
                    ['name'=>'Emily Rodriguez','role'=>'Outreach Coordinator','img'=>'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=300'],
                    ['name'=>'David Osei','role'=>'Field Manager','img'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300'],
                ];
                @endphp
                @foreach($team as $member)
                <div class="team-card">
                    <div class="team-img">
                        <img src="{{ $member['img'] }}" alt="{{ $member['name'] }}">
                        <div class="team-social">
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="team-info">
                        <h4>{{ $member['name'] }}</h4>
                        <span class="team-role">{{ $member['role'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section class="section-testimonials bg-light">
        <div class="container">
            <div class="section-header text-center">
                <h2>What People Say</h2>
                <div class="section-divider"><span></span></div>
            </div>
            <div class="testimonials-slider">
                <div class="testimonial-item active">
                    <div class="testimonial-content">
                        <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                        <p>Welfare NGO transformed our village. We now have access to clean water and our children attend school. I am forever grateful for their commitment and compassion.</p>
                        <div class="testimonial-author">
                            <img src="https://images.unsplash.com/photo-1531123897727-8f129e1688ce?w=80" alt="Amara Diallo">
                            <div>
                                <strong>Amara Diallo</strong>
                                <span>Community Leader, Mali</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="testimonial-content">
                        <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                        <p>Volunteering with Welfare was one of the most rewarding experiences of my life. The team is professional, passionate, and truly makes a measurable impact.</p>
                        <div class="testimonial-author">
                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=80" alt="James Park">
                            <div>
                                <strong>James Park</strong>
                                <span>Volunteer, New York</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="testimonial-content">
                        <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                        <p>I've been donating to Welfare for three years. Their transparency and consistent reporting gives me full confidence that my contributions are being used wisely.</p>
                        <div class="testimonial-author">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=80" alt="Lisa Thompson">
                            <div>
                                <strong>Lisa Thompson</strong>
                                <span>Regular Donor, Chicago</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-dots" id="testimonial-dots">
                <span class="dot active" data-index="0"></span>
                <span class="dot" data-index="1"></span>
                <span class="dot" data-index="2"></span>
            </div>
        </div>
    </section>

    <!-- BLOG / NEWS SECTION -->
    <section class="section-blog bg-white">
        <div class="container">
            <div class="section-header text-center">
                <h2>Latest News</h2>
                <div class="section-divider"><span></span></div>
                <p class="section-subtitle">Stay updated with our latest projects, stories, and announcements.</p>
            </div>
            <div class="blog-grid">
                @php
                $posts = [
                    ['title'=>'New Water Wells Completed in Rural Kenya','date'=>'June 15, 2024','cat'=>'Projects','img'=>'https://images.unsplash.com/photo-1541544537156-7627a7a4aa1c?w=500','excerpt'=>'Thanks to our donors, 12 new wells have been completed in rural Kenya, providing clean water to over 3,000 people.'],
                    ['title'=>'Annual Charity Gala Raises Record Funds','date'=>'May 28, 2024','cat'=>'Events','img'=>'https://images.unsplash.com/photo-1511578314322-379afb476865?w=500','excerpt'=>'Our annual gala raised over $450,000 — the highest amount in our organization\'s 20-year history.'],
                    ['title'=>'Volunteer Training Program Launches in 10 Cities','date'=>'May 10, 2024','cat'=>'Volunteering','img'=>'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=500','excerpt'=>'We are expanding our volunteer training program to 10 new cities across the US, preparing community leaders.'],
                ];
                @endphp
                @foreach($posts as $post)
                <div class="blog-card">
                    <div class="blog-img">
                        <a href="{{ url('/blog') }}">
                            <img src="{{ $post['img'] }}" alt="{{ $post['title'] }}">
                        </a>
                        <span class="blog-cat">{{ $post['cat'] }}</span>
                    </div>
                    <div class="blog-body">
                        <div class="blog-meta">
                            <span><i class="fas fa-calendar-alt"></i> {{ $post['date'] }}</span>
                        </div>
                        <h3><a href="{{ url('/blog') }}">{{ $post['title'] }}</a></h3>
                        <p>{{ $post['excerpt'] }}</p>
                        <a href="{{ url('/blog') }}" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- PARTNERS / SPONSORS SECTION -->
    <section class="section-partners">
        <div class="container">
            <div class="section-header text-center">
                <h2>Our Partners</h2>
                <div class="section-divider"><span></span></div>
            </div>
            <div class="partners-grid">
                <div class="partner-logo">UNICEF</div>
                <div class="partner-logo">WHO</div>
                <div class="partner-logo">Red Cross</div>
                <div class="partner-logo">USAID</div>
                <div class="partner-logo">World Bank</div>
                <div class="partner-logo">UNESCO</div>
            </div>
        </div>
    </section>

@endsection
