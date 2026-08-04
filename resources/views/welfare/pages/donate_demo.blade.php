@extends('welfare.layouts.app')

@section('title', 'Donate (Demo) - Pertubuhan Gabungan MUKMIN Nasional')

@section('content')
<section class="section-donate bg-white" style="padding: 60px 0;">
    <div class="container" style="max-width: 640px; margin: 0 auto; padding: 20px;">
        <div style="padding: 12px 16px; border-radius: 6px; margin-bottom: 24px; background: #fffbeb; color: #92400e; border: 1px solid #fde68a; text-align: center; font-size: 0.9rem;">
            <i class="fas fa-flask"></i> <strong>Testing environment</strong> — this demo form uses live KiplePay sandbox payments. Not linked in site navigation.
        </div>

        <div class="section-header text-center" style="margin-bottom: 45px;">
            <h2>Make a Donation</h2>
            <div class="section-divider"><span></span></div>
            <p class="section-subtitle">Support our community programmes through a secure online payment.</p>
        </div>

        @if(session('error'))
            <div style="padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('welfare.donate-demo.store') }}" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label style="display: block; font-weight: 500; margin-bottom: 5px; color: var(--color-heading);">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Name as per NRIC" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 4px; outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--color-primary)';" onblur="this.style.borderColor='var(--color-border)';" required>
                </div>
                <div>
                    <label style="display: block; font-weight: 500; margin-bottom: 5px; color: var(--color-heading);">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 4px; outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--color-primary)';" onblur="this.style.borderColor='var(--color-border)';" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label style="display: block; font-weight: 500; margin-bottom: 5px; color: var(--color-heading);">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 4px; outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--color-primary)';" onblur="this.style.borderColor='var(--color-border)';" required>
                </div>
                <div>
                    <label style="display: block; font-weight: 500; margin-bottom: 5px; color: var(--color-heading);">Donation Amount (RM)</label>
                    <input type="number" name="amount" step="0.01" min="1" value="{{ old('amount') }}" placeholder="0.00" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 4px; outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--color-primary)';" onblur="this.style.borderColor='var(--color-border)';" required>
                </div>
            </div>

            <div>
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: var(--color-heading);">Pay Securely via</label>
                <div style="display: flex; align-items: center; gap: 12px; background: #f8fafc; border: 1px solid var(--color-border); border-radius: 6px; padding: 14px 16px;">
                    <i class="fas fa-shield-alt" style="color: #16a34a; font-size: 1.4rem;"></i>
                    <div>
                        <div style="font-weight: 600; color: var(--color-heading);">KiplePay</div>
                        <div style="font-size: 0.85rem; color: #64748b;">FPX, credit/debit cards, and e-wallets</div>
                    </div>
                </div>
            </div>

            <div>
                <label style="display: block; font-weight: 500; margin-bottom: 5px; color: var(--color-heading);">Message (Optional)</label>
                <textarea name="message" rows="3" placeholder="Share a note with your donation" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 4px; outline: none; transition: border-color 0.3s; resize: vertical;" onfocus="this.style.borderColor='var(--color-primary)';" onblur="this.style.borderColor='var(--color-border)';">{{ old('message') }}</textarea>
            </div>

            @include('welfare.partials.recaptcha')

            <div>
                <button type="submit" class="btn btn-primary" style="border: none; border-radius: 4px; font-size: 16px; width: 100%; line-height: 20px; padding: 12px 28px;">
                    Proceed to Payment <i class="fas fa-chevron-right" style="margin-left: 6px;"></i>
                </button>
            </div>

            <p style="text-align: center; font-size: 0.85rem; color: #64748b; margin: 0;">
                <i class="fas fa-lock"></i> Secure SSL encrypted transaction
            </p>
        </form>
    </div>
</section>
@endsection

@push('styles')
<style>
@media (max-width: 600px) {
    .section-donate form > div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endpush
