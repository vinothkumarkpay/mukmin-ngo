@php
    $recaptchaEnabled = filled(config('services.recaptcha.site_key')) && filled(config('services.recaptcha.secret_key'));
    $recaptchaSiteKey = config('services.recaptcha.site_key');
@endphp

@if($recaptchaEnabled)
    <div>
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" value="">
        @error('g-recaptcha-response')
            <div style="color: #b91c1c; font-size: 0.875rem; margin-bottom: 8px;">{{ $message }}</div>
        @enderror
        <p style="margin: 0; font-size: 0.8rem; color: #64748b; line-height: 1.5;">
            This site is protected by reCAPTCHA and the Google
            <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer" style="color: #0b4f8c;">Privacy Policy</a>
            and
            <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer" style="color: #0b4f8c;">Terms of Service</a>
            apply.
        </p>
    </div>

    @once
        @push('scripts')
            <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
            <script>
                (function () {
                    var siteKey = @json($recaptchaSiteKey);
                    document.querySelectorAll('form[method="POST"]').forEach(function (form) {
                        if (!form.querySelector('#g-recaptcha-response')) {
                            return;
                        }

                        form.addEventListener('submit', function (event) {
                            if (form.dataset.recaptchaSubmitting === '1') {
                                return;
                            }

                            event.preventDefault();

                            var tokenInput = form.querySelector('#g-recaptcha-response');
                            var submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');

                            submitButtons.forEach(function (button) {
                                button.disabled = true;
                            });

                            grecaptcha.ready(function () {
                                grecaptcha.execute(siteKey, { action: 'donate' }).then(function (token) {
                                    tokenInput.value = token;
                                    form.dataset.recaptchaSubmitting = '1';
                                    form.submit();
                                }).catch(function () {
                                    submitButtons.forEach(function (button) {
                                        button.disabled = false;
                                    });
                                    alert('Captcha verification failed. Please try again.');
                                });
                            });
                        });
                    });
                })();
            </script>
        @endpush
    @endonce
@endif
