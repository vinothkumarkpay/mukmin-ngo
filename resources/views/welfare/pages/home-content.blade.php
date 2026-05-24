{{-- WordPress demo "Home" page structure (cmsms_row / cmsms_column shortcodes) --}}

{{-- Rotating Banners / Hero --}}
<div class="cmsms_row cmsms_color_scheme_default">
    <div class="cmsms_row_outer_parent">
        <div class="cmsms_row_outer">
            <div class="cmsms_row_inner cmsms_row_fullwidth">
                <div class="cmsms_row_margin">
                    <div class="cmsms_column one_first">
                        <div class="cmsms_slider">
                            <div class="rev_slider_wrapper fullwidthbanner-container">
                                <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=1920&q=80"
                                     alt="Welfare NGO" style="width:100%;height:auto;display:block;min-height:420px;object-fit:cover;" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Icon boxes: Mission, Events, Support, Volunteer --}}
<div class="cmsms_row cmsms_color_scheme_default">
    <div class="cmsms_row_outer_parent" style="padding-bottom:50px;">
        <div class="cmsms_row_outer">
            <div class="cmsms_row_inner">
                <div class="cmsms_row_margin">
                    @foreach($iconBoxes as $box)
                    <div class="cmsms_column one_fourth">
                        <div class="cmsms_icon_box cmsms_icon_box_top box_icon_type_icon {{ $box['icon'] }}">
                            <div class="icon_box_inner">
                                <h2 class="icon_box_heading">{{ $box['title'] }}</h2>
                                <div class="icon_box_text"><p>{{ $box['text'] }}</p></div>
                                <a href="{{ route('welfare.about') }}" class="cmsms_button icon_box_button"><span>Read more</span></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Vision, Mission, Engines of Impact, Strategic Initiatives --}}
<div class="cmsms_row cmsms_color_scheme_default">
    <div class="cmsms_row_outer_parent" style="padding-bottom:50px;">
        <div class="cmsms_row_outer">
            <div class="cmsms_row_inner">
                <div class="cmsms_row_margin">
                    <div class="cmsms_column one_first">
                        <h2 style="margin-bottom: 20px;">VISION</h2>
                        <p style="font-size: 18px; margin-bottom: 40px;">To become a national community development ecosystem that advances societal well-being, shared prosperity, and inclusive, sustainable progress.</p>
                        
                        <h2 style="margin-bottom: 20px;">MISSION</h2>
                        <ul style="font-size: 16px; margin-bottom: 40px; list-style-type: disc; padding-left: 20px;">
                            <li>To unify and empower community networks through strategic and structured national coordination</li>
                            <li>To drive cross-sector collaboration between communities, government, industry, and stakeholders</li>
                            <li>To develop human capital and leadership grounded in values, skills, and future-ready competitiveness</li>
                            <li>To strengthen socio-economic development through innovative, inclusive, and sustainable approaches</li>
                            <li>To translate policy, research, and dialogue into high-impact community-based initiatives</li>
                        </ul>
                        <a href="{{ route('welfare.about') }}" class="cmsms_button"><span>Find out more</span></a>
                        
                        <hr style="margin: 50px 0;">
                        
                        <h2 style="margin-bottom: 20px;">THREE ENGINES OF IMPACT</h2>
                        <p style="font-size: 16px; margin-bottom: 30px;">MUKMIN operates through three complementary engines strengthen collaboration, align stakeholders, and drive meaningful community impact at scale.</p>
                        
                        <div style="margin-bottom: 20px;">
                            <h3 style="margin-bottom: 5px; color: #d43c18;">FIKRAH (Strategic Think Tank)</h3>
                            <p>Driving policy, research and long-term strategy to shape stronger communities and future-ready institutions.</p>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <h3 style="margin-bottom: 5px; color: #d43c18;">Pertubuhan Gabungan MUKMIN Nasional (National Coordination Platform)</h3>
                            <p>Uniting organisations, aligning stakeholders and coordinating collective action for nationwide impact.</p>
                        </div>
                        <div style="margin-bottom: 40px;">
                            <h3 style="margin-bottom: 5px; color: #d43c18;">Yayasan MUKMIN (Community Impact Foundation)</h3>
                            <p>Transforming ideas into measurable outcomes through education, outreach and community-driven initiatives.</p>
                        </div>
                        <a href="{{ route('welfare.ecosystem') }}" class="cmsms_button"><span>Find out more</span></a>
                        
                        <hr style="margin: 50px 0;">
                        
                        <h2 style="margin-bottom: 20px;">STRATEGIC INITIATIVES</h2>
                        <p style="font-size: 16px; margin-bottom: 30px;">MUKMIN focuses on strengthening communities through strategic focus areas that advance inclusive development, future-ready leadership and sustainable national progress.</p>
                        
                        <div style="margin-bottom: 20px;">
                            <h4 style="margin-bottom: 5px;">Socio-Economic Mobility</h4>
                            <p><strong>From Assistance to Sustainable Participation</strong><br>Empowering communities to move beyond dependency through economic participation, enterprise development and sustainable growth opportunities.</p>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <h4 style="margin-bottom: 5px;">Education & Future Readiness</h4>
                            <p><strong>Building Competitive Human Capital</strong><br>Developing talent through integrated academic, leadership, and skills-based pathways aligned with industry needs and global relevance.</p>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <h4 style="margin-bottom: 5px;">Leadership & Capacity Building</h4>
                            <p><strong>Values-Driven Leaders for Impact</strong><br>Nurturing principled leaders equipped to contribute at community, national and global levels through leadership development and institutional capacity building.</p>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <h4 style="margin-bottom: 5px;">Entrepreneurship & Innovation</h4>
                            <p><strong>Enabling Participation in the Future Economy</strong><br>Strengthening innovation, digital capabilities and entrepreneurial growth to increase participation in the evolving regional and global economy.</p>
                        </div>
                        <div style="margin-bottom: 40px;">
                            <h4 style="margin-bottom: 5px;">Faith, Identity & Social Ukhwah</h4>
                            <p><strong>Strengthening Communities Through Shared Values</strong><br>Anchoring development in values, identity and unity to build resilient, connected and socially cohesive communities.</p>
                        </div>
                        <a href="{{ route('welfare.impact') }}" class="cmsms_button"><span>Find out more</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Adopt A Graduate Programme (Replaces Featured Campaign) --}}
<div class="cmsms_row cmsms_color_scheme_default">
    <div class="cmsms_row_outer_parent" style="padding-bottom:60px;padding-top:10px;">
        <div class="cmsms_row_outer">
            <div class="cmsms_row_inner">
                <div class="cmsms_row_margin">
                    <div class="cmsms_column one_first">
                        <div style="display: flex; gap: 40px; align-items: center; background: #f9f9f9; padding: 40px; border-radius: 8px;">
                            <div style="flex: 1;">
                                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&q=80" alt="Graduate" style="border-radius: 8px; width: 100%;">
                            </div>
                            <div style="flex: 1;">
                                <h2 style="margin-bottom: 10px;">Adopt A Graduate Programme</h2>
                                <h4 style="color: #666; margin-bottom: 20px;">Building Pathways From Education to Employment</h4>
                                <p style="margin-bottom: 15px;">The Adopt A Graduate Programme connects graduates with industry opportunities through strategic partnerships, mentorship and workforce placement initiatives aimed at strengthening future talent development and employability.</p>
                                <p style="margin-bottom: 15px;">Through collaboration with corporate partners, institutions and employers, the programme bridges the gap between education and career readiness while creating sustainable pathways for young professionals to thrive in the workforce.</p>
                                <p style="margin-bottom: 20px;">More than 25 companies and strategic partners are currently being engaged to support graduate adoption opportunities across multiple industries and sectors — reflecting MUKMIN’s long-term commitment towards human capital development, talent mobility and inclusive socio-economic advancement.</p>
                                <a href="#" class="cmsms_button"><span>Support Now</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Cause List carousel --}}
<div class="cmsms_row cmsms_color_scheme_fifth">
    <div class="cmsms_row_outer_parent" style="padding-top:50px;padding-bottom:50px;">
        <div class="cmsms_row_outer">
            <div class="cmsms_row_inner">
                <div class="cmsms_row_margin">
                    <div class="cmsms_column one_first">
                        <div class="cmsms_heading_wrap cmsms_heading_align_left">
                            <h2 class="cmsms_heading">Cause List</h2>
                        </div>
                        <div class="cmsms_campaigns">
                            <div class="cmsms_owl_slider cmsms_home_campaigns_slider owl-carousel owl-theme">
                                @foreach($campaigns as $campaign)
                                <div class="cmsms_owl_slider_item">
                                    @include('welfare.partials.campaign-horizontal', ['campaign' => $campaign])
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div style="margin-top: 30px; text-align: center;">
                            <a href="{{ route('welfare.serve') }}" class="cmsms_button"><span>Find out more</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Key Engagements --}}
<div class="cmsms_row cmsms_color_scheme_default">
    <div class="cmsms_row_outer_parent" style="padding-top:60px;padding-bottom:60px;">
        <div class="cmsms_row_outer">
            <div class="cmsms_row_inner">
                <div class="cmsms_row_margin">
                    <div class="cmsms_column one_first">
                        <div class="cmsms_heading_wrap cmsms_heading_align_center">
                            <h2 class="cmsms_heading">Key Engagements</h2>
                        </div>
                        <div style="display: flex; gap: 40px; margin-top: 30px;">
                            <div style="flex: 2;">
                                <ul style="list-style: none; padding: 0;">
                                    <li style="margin-bottom: 20px; font-size: 18px;"><strong>11 MAY 2026</strong> &nbsp; MUKMIN Extraordinary General Meeting (EGM)</li>
                                    <li style="margin-bottom: 20px; font-size: 18px;"><strong>31 MAY 2026</strong> &nbsp; MUKMIN Annual General Meeting (AGM)</li>
                                </ul>
                            </div>
                            <div style="flex: 1; background: #f4f4f4; padding: 30px; border-radius: 8px;">
                                <h3 style="margin-bottom: 20px;">MUKMIN Updates</h3>
                                <ul style="list-style: none; padding: 0;">
                                    <li style="margin-bottom: 10px; font-size: 16px;"><a href="#" style="color: #333; text-decoration: none;">Programme Updates</a></li>
                                    <li style="margin-bottom: 10px; font-size: 16px;"><a href="#" style="color: #333; text-decoration: none;">Official Notices</a></li>
                                    <li style="font-size: 16px;"><a href="#" style="color: #333; text-decoration: none;">Media & Highlights</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Parallax quote row --}}
<div class="cmsms_row cmsms_color_scheme_default" style="background-image:url('https://images.unsplash.com/photo-1488521787991-ed7b68cf0ff0?w=1920&q=80');background-size:cover;background-position:top center;">
    <div class="cmsms_row_overlay" style="background-color:rgba(22,22,22,0.7);"></div>
    <div class="cmsms_row_outer_parent" style="padding-top:80px;padding-bottom:80px;">
        <div class="cmsms_row_outer">
            <div class="cmsms_row_inner">
                <div class="cmsms_row_margin">
                    <div class="cmsms_column one_half"></div>
                    <div class="cmsms_column one_half">
                        <div class="cmsms_heading_wrap cmsms_heading_align_left">
                            <h1 class="cmsms_heading" style="color:#ffffff;">No one has ever become poor by giving</h1>
                        </div>
                        <div class="cmsms_text">
                            <p style="color:rgba(255,255,255,0.8);">Your contribution supports more than a single programme — it strengthens a wider ecosystem dedicated to community development, leadership growth, education access and long-term social impact.<br><br>Through strategic collaboration and community-driven initiatives, every contribution helps MUKMIN and its ecosystem partners create meaningful change across communities, institutions and future generations.</p>
                        </div>
                        <a href="{{ route('welfare.donate') }}" class="cmsms_button"><span>Donate Now!</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Latest News (puzzle blog layout) --}}
<div class="cmsms_row cmsms_color_scheme_default">
    <div class="cmsms_row_outer_parent" style="padding-top:60px;padding-bottom:60px;">
        <div class="cmsms_row_outer">
            <div class="cmsms_row_inner">
                <div class="cmsms_row_margin">
                    <div class="cmsms_column one_first">
                        <div class="cmsms_heading_wrap cmsms_heading_align_center">
                            <h1 class="cmsms_heading">Latest News</h1>
                        </div>
                        <div class="cmsms_wrap_blog entry-summary" id="blog_home">
                            <div class="blog columns mode_puzzle">
                                @foreach($posts as $post)
                                <article class="post type-post format-standard">
                                    <figure class="cmsms_img_wrap">
                                        <a href="{{ route('welfare.blog.show', $post['slug']) }}">
                                            <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" />
                                        </a>
                                    </figure>
                                    <header class="cmsms_post_header entry-header">
                                        <h2 class="cmsms_post_title entry-title">
                                            <a href="{{ route('welfare.blog.show', $post['slug']) }}">{{ $post['title'] }}</a>
                                        </h2>
                                        <div class="cmsms_post_info entry-meta">
                                            <abbr class="published" title="{{ $post['date'] }}">{{ \Carbon\Carbon::parse($post['date'])->format('j F Y') }}</abbr>
                                        </div>
                                    </header>
                                    <div class="cmsms_post_cont entry-content">
                                        <p>{{ $post['excerpt'] }}</p>
                                    </div>
                                    <footer style="margin-top: 15px;">
                                        <a href="{{ route('welfare.news') }}" style="color: #d43c18; font-weight: bold; text-decoration: none;">Read More / View Gallery</a>
                                    </footer>
                                </article>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
