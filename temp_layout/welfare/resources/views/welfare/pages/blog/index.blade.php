@extends('welfare.layouts.app')

@section('title', 'Blog')
@php $layout = 'fullwidth'; @endphp

@section('headline')
    @include('welfare.partials.headline', ['title' => 'Blog'])
@endsection

@section('content')
<div class="middle_content entry" role="main">
    <div class="cmsms_wrap_blog entry-summary">
        <div class="blog standard">
            @foreach($posts as $post)
            <article class="post type-post format-standard">
                <figure class="cmsms_img_wrap">
                    <a href="{{ route('welfare.blog.show', $post['slug']) }}"><img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" /></a>
                </figure>
                <header class="cmsms_post_header entry-header">
                    <h2 class="cmsms_post_title entry-title">
                        <a href="{{ route('welfare.blog.show', $post['slug']) }}">{{ $post['title'] }}</a>
                    </h2>
                    <div class="cmsms_post_info entry-meta">
                        <abbr class="published">{{ \Carbon\Carbon::parse($post['date'])->format('F j, Y') }}</abbr>
                        <span class="cmsms_post_author">by {{ $post['author'] }}</span>
                    </div>
                </header>
                <div class="cmsms_post_cont entry-content"><p>{{ $post['excerpt'] }}</p></div>
            </article>
            @endforeach
        </div>
    </div>
</div>
@endsection
