<div
    class="membership-modal-overlay"
    id="membership-registration-gate-modal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="membership-registration-gate-title"
    data-friends-url="{{ route('welfare.membership.friends') }}"
    data-form-urls="{{ implode('|', [
        route('welfare.community-aid'),
        route('welfare.feedback'),
        route('welfare.volunteer'),
        route('welfare.mentor'),
        route('welfare.partner'),
    ]) }}"
    hidden
>
    <div class="membership-modal">
        <button type="button" class="membership-modal-close" id="membership-registration-gate-close" aria-label="Close">&times;</button>
        <h3 id="membership-registration-gate-title">Membership Registration</h3>
        <p class="membership-modal-subtitle">Are you a registered MUKMIN member?</p>
        <form id="membership-registration-gate-form">
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
