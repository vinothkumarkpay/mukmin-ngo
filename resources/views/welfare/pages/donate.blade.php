@extends('welfare.layouts.app')

@section('title', 'Donate')

@section('content')
<section class="section-donate bg-white" style="padding: 60px 0;">
    <div class="container" style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div class="section-header text-center" style="margin-bottom: 45px;">
            <h2>Every Contribution Begins With A Shared Purpose</h2>
            <div class="section-divider"><span></span></div>
            <p class="section-subtitle" style="margin-bottom: 16px;">Thank you for your willingness to support the MUKMIN movement and the communities we serve.</p>
            <p class="section-subtitle">Our donation platform is currently under development and will be launched soon to facilitate future contributions and impact initiatives.</p>
        </div>

        @if(false) {{-- Temporarily hide donation form until launch --}}
        <form method="POST" action="{{ route('welfare.donate.submit') }}" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf
            
            <div>
                <label style="display: block; font-weight: 500; margin-bottom: 10px; color: var(--color-heading); text-align: center;">Select Donation Amount</label>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 20px;">
                    @foreach([25, 50, 100, 250] as $amount)
                    <label style="position: relative; display: block; text-align: center; cursor: pointer;">
                        <input type="radio" name="amount" value="{{ $amount }}" {{ (request('amount') == $amount || ($loop->first && !request('amount'))) ? 'checked' : '' }} style="position: absolute; opacity: 0; width: 0; height: 0;" class="amount-radio">
                        <span class="amount-card" style="display: block; padding: 12px 0; border: 2px solid var(--color-border); border-radius: 4px; font-weight: 600; color: var(--color-heading); transition: all var(--transition);">${{ $amount }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label style="display: block; font-weight: 500; margin-bottom: 5px; color: var(--color-heading);">Or Enter Custom Amount ($)</label>
                <input type="number" name="custom_amount" placeholder="Other Amount" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 4px; outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--color-primary)';" onblur="this.style.borderColor='var(--color-border)';">
            </div>

            <div>
                <label style="display: block; font-weight: 500; margin-bottom: 5px; color: var(--color-heading);">Full Name</label>
                <input type="text" name="name" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 4px; outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--color-primary)';" onblur="this.style.borderColor='var(--color-border)';" required>
            </div>

            <div>
                <label style="display: block; font-weight: 500; margin-bottom: 5px; color: var(--color-heading);">Email Address</label>
                <input type="email" name="email" placeholder="name@example.com" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 4px; outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--color-primary)';" onblur="this.style.borderColor='var(--color-border)';" required>
            </div>

            <div style="margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="border: none; border-radius: 4px; font-size: 16px; width: 100%; line-height: 20px; padding: 12px 28px;">Complete Donation</button>
            </div>
        </form>
        @endif
    </div>
</section>

@if(false)
<style>
/* Style for active radio button selection */
.amount-radio:checked + .amount-card {
    background-color: var(--color-primary) !important;
    border-color: var(--color-primary) !important;
    color: white !important;
}
.amount-card:hover {
    border-color: var(--color-primary) !important;
}
</style>
@endif
@endsection
