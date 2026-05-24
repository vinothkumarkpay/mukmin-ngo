<div class="nav_wrap">
    <ul id="navigation" class="navigation">
        @foreach(config('welfare.nav', []) as $item)
            <li class="{{ request()->routeIs($item['route']) ? 'current-menu-item' : '' }}">
                <a href="{{ route($item['route']) }}"><span>{{ $item['label'] }}</span></a>
            </li>
        @endforeach
    </ul>
</div>
