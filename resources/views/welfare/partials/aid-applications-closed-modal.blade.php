@unless(config('welfare.aid_applications_open'))
<div
    class="membership-modal-overlay"
    id="aid-applications-closed-modal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="aid-applications-closed-title"
    data-aid-url="{{ route('welfare.community-aid') }}"
    data-home-url="{{ route('welfare.home') }}"
    @unless(session('aid_applications_closed')) hidden @endunless
>
    <div class="membership-modal">
        <button type="button" class="membership-modal-close" id="aid-applications-closed-close" aria-label="Close">&times;</button>
        <h3 id="aid-applications-closed-title">The Application Window Is Now Closed</h3>
        <p class="membership-modal-subtitle">Thank you for your interest in MUKMIN Nasional’s Aid Programme. The current application window has officially closed.</p>
        <p class="membership-modal-subtitle">Please stay tuned to our website and official social media channels for announcements on the next application window.</p>
        <p class="membership-modal-subtitle">Thank you for your understanding and continued interest in MUKMIN Nasional.</p>
        <button type="button" class="membership-modal-submit" id="aid-applications-closed-ok">Back to Home</button>
    </div>
</div>
@endunless
