<button
    type="button"
    class="member-card"
    data-member-name="{{ $member['name'] }}"
    data-member-role="{{ $member['role'] ?? '' }}"
    data-member-org="{{ $member['org'] ?? '' }}"
    data-member-tag="{{ $member['tag'] ?? '' }}"
    data-member-image="{{ $member['image'] }}"
    data-member-committee="{{ $committee }}"
    aria-label="View profile of {{ $member['name'] }}"
>
    <div class="member-avatar">
        <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}">
    </div>
    <h4 class="member-name">{{ $member['name'] }}</h4>
    @if(!empty($showRole) && !empty($member['role']))
        <span class="member-role">{{ $member['role'] }}</span>
    @endif
    @if(!empty($showOrg) && !empty($member['org']))
        <span class="member-org">{{ $member['org'] }}</span>
    @endif
    @if(!empty($showTag) && !empty($member['tag']))
        <span class="member-tag">{{ $member['tag'] }}</span>
    @endif
    <span class="member-card-hint">View profile</span>
</button>
