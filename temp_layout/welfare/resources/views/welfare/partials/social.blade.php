<div class="social_wrap">
    <ul>
        @foreach(config('welfare.social', []) as $social)
            <li>
                <a href="{{ $social['url'] }}" class="{{ $social['icon'] }}" title="{{ $social['label'] }}" target="_blank" rel="noopener noreferrer"></a>
            </li>
        @endforeach
    </ul>
</div>
