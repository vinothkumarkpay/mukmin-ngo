@php
    $recaptchaEnabled = filled(config('services.recaptcha.site_key')) && filled(config('services.recaptcha.secret_key'));
@endphp

@if($recaptchaEnabled)
    <div style="display: flex; flex-direction: column; align-items: center;">
        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
        @error('g-recaptcha-response')
            <div style="color: #b91c1c; font-size: 0.875rem; margin-top: 8px; text-align: center;">{{ $message }}</div>
        @enderror
    </div>

    @once
        @push('scripts')
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        @endpush
    @endonce
@endif
