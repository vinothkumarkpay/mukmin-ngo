@extends('welfare.layouts.app')

@section('title', $post['title'])
@php $layout = 'fullwidth'; @endphp

@section('headline')
    @include('welfare.partials.headline', ['title' => $post['title'], 'subtitle' => 'Blog'])
@endsection

@section('content')
<div class="middle_content entry" role="main">
    <article class="post type-post format-standard">
        <figure class="cmsms_img_wrap"><img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" /></figure>
        <div class="cmsms_post_info entry-meta">
            <abbr class="published">{{ \Carbon\Carbon::parse($post['date'])->format('F j, Y') }}</abbr>
            <span class="cmsms_post_author">by {{ $post['author'] }}</span>
        </div>
        <div class="cmsms_post_cont entry-content cmsms_text">{!! $post['content'] !!}</div>
    </article>
    <p><a href="{{ route('welfare.blog.index') }}">&larr; Back to Blog</a></p>
</div>
@endsection
