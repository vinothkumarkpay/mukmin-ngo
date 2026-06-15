<div
    class="membership-modal-overlay"
    id="community-aid-gate-modal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="community-aid-gate-title"
    data-community-aid-url="{{ route('welfare.community-aid') }}"
    data-friends-url="{{ route('welfare.membership.friends') }}"
    hidden
>
    <div class="membership-modal">
        <button type="button" class="membership-modal-close" id="community-aid-gate-close" aria-label="Close">&times;</button>
        <h3 id="community-aid-gate-title">Membership Registration</h3>
        <p class="membership-modal-subtitle">Are you a registered MUKMIN member?</p>
        <form id="community-aid-gate-form">
            <div class="membership-modal-options">
                <label class="membership-modal-option">
                    <input type="radio" name="is_member" value="yes" required>
                    <span>Yes</span>
                </label>
                <label class="membership-modal-option">
                    <input type="radio" name="is_member" value="no">
                    <span>No</span>
                </label>
            </div>
            <button type="submit" class="membership-modal-submit">Continue</button>
        </form>
    </div>
</div>
