{{-- WordPress demo "Home" page structure (cmsms_row / cmsms_column shortcodes) --}}

{{-- Revolution Slider placeholder (fullwidth) --}}
<div class="cmsms_row cmsms_color_scheme_default">
    <div class="cmsms_row_outer_parent">
        <div class="cmsms_row_outer">
            <div class="cmsms_row_inner cmsms_row_fullwidth">
                <div class="cmsms_row_margin">
                    <div class="cmsms_column one_first">
                        <div class="cmsms_slider">
                            <div class="rev_slider_wrapper fullwidthbanner-container">
                                <img src="https://demo.welfare.cmsmasters.net/wp-content/uploads/sites/3/2015/05/photodune-10429351-crying-child-masonry-puzzle.jpg"
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

{{-- Featured campaign --}}
<div class="cmsms_row cmsms_color_scheme_default">
    <div class="cmsms_row_outer_parent" style="padding-bottom:60px;padding-top:10px;">
        <div class="cmsms_row_outer">
            <div class="cmsms_row_inner">
                <div class="cmsms_row_margin">
                    <div class="cmsms_column one_first">
                        @include('welfare.partials.campaign-featured', ['campaign' => $featuredCampaign])
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Parallax quote row --}}
<div class="cmsms_row cmsms_color_scheme_default" style="background-image:url('https://demo.welfare.cmsmasters.net/wp-content/uploads/sites/3/2015/05/photodune-10429351-crying-child-masonry-puzzle.jpg');background-size:cover;background-position:top center;">
    <div class="cmsms_row_overlay" style="background-color:rgba(22,22,22,0.4);"></div>
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
                            <p style="color:rgba(255,255,255,0.8);">Lorem ipsum dolor sit amet, consectetur cing elit. Suspendisse suscipit sagittis leo sit met condimentum estibulum dignissim posuere cubilia Curae.</p>
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
                                            <abbr class="published" title="{{ $post['date'] }}">{{ \Carbon\Carbon::parse($post['date'])->format('F j, Y') }}</abbr>
                                        </div>
                                    </header>
                                    <div class="cmsms_post_cont entry-content">
                                        <p>{{ $post['excerpt'] }}</p>
                                    </div>
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

{{-- Our Donators --}}
<div class="cmsms_row cmsms_color_scheme_fifth">
    <div class="cmsms_row_outer_parent" style="padding-top:60px;padding-bottom:70px;">
        <div class="cmsms_row_outer">
            <div class="cmsms_row_inner">
                <div class="cmsms_row_margin">
                    <div class="cmsms_column one_first">
                        <div class="cmsms_heading_wrap cmsms_heading_align_center">
                            <h1 class="cmsms_heading">Our Donators</h1>
                        </div>
                        <div class="cmsms_row_margin">
                            @foreach($donators as $donator)
                            <div class="cmsms_column one_fourth">
                                <article class="donation type-donation">
                                    <figure class="cmsms_img_wrap">
                                        <img src="{{ $donator['image'] }}" alt="{{ $donator['name'] }}" />
                                    </figure>
                                    <header class="cmsms_donation_header entry-header">
                                        <h4 class="cmsms_donation_title entry-title">{{ $donator['name'] }}</h4>
                                    </header>
                                    <div class="cmsms_donation_info">
                                        <span class="cmsms_donation_amount">${{ number_format($donator['amount']) }}</span>
                                        <span class="cmsms_donation_campaign">{{ $donator['campaign'] }}</span>
                                    </div>
                                </article>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Twitter stripe --}}
<div class="cmsms_row cmsms_color_scheme_third" style="background-color:#d43c18;">
    <div class="cmsms_row_outer_parent" style="padding-top:20px;padding-bottom:20px;">
        <div class="cmsms_row_outer">
            <div class="cmsms_row_inner">
                <div class="cmsms_row_margin">
                    <div class="cmsms_column one_first">
                        <div class="cmsms_twitter cmsms_twitter_strip">
                            <p style="color:#fff;text-align:center;margin:0;">
                                Follow us on Twitter <a href="#" style="color:#fff;">@cmsmasters</a> — stay updated on our latest campaigns and impact stories.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
