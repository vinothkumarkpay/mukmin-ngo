@extends('welfare.layouts.app')

@section('title', 'Contact Us - Pertubuhan Gabungan MUKMIN Nasional')

@section('content')
<section class="section-contact bg-white" style="padding: 60px 0;">
    <div class="container">
        <div class="section-header text-center" style="margin-bottom: 45px;">
            <h2>Let's Connect</h2>
            <div class="section-divider"><span></span></div>
            <p class="section-subtitle" style="max-width: 760px; margin: 0 auto 16px;">
                Have a question? Looking for someone to speak to?
            </p>
            <p class="section-subtitle" style="max-width: 760px; margin: 0 auto 16px;">
                Whether you are seeking collaboration, exploring opportunities or looking to learn more about MUKMIN, our team is here to connect, support and guide you.
            </p>
            <p class="section-subtitle" style="max-width: 760px; margin: 0 auto;">
                Together, we can strengthen communities, unlock opportunities and drive meaningful impact through collaboration and shared purpose.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; max-width: 1000px; margin: 0 auto; padding: 20px;">
            <div>
                <h3 style="margin-bottom: 20px; font-size: 20px; color: var(--color-heading);">Contact Information</h3>
                <div style="font-size: 14px; line-height: 24px; color: #555; margin-bottom: 30px;">
                    <p style="margin-bottom: 12px;">
                        <i class="fas fa-map-marker-alt" style="color: var(--color-primary); margin-right: 10px; width: 15px;"></i>
                        <strong>Address:</strong><br>
                        <span style="display: inline-block; padding-left: 25px; margin-top: 4px;">
                            {{ config('welfare.org_name') }}<br>
                            {{ config('welfare.address') }}<br>
                            {{ config('welfare.postal') }}
                        </span>
                    </p>
                    <p style="margin-bottom: 12px;">
                        <i class="fas fa-phone" style="color: var(--color-primary); margin-right: 10px; width: 15px;"></i>
                        <strong>Mobile:</strong> {{ config('welfare.phone') }}
                    </p>
                    <p style="margin-bottom: 12px;">
                        <i class="fas fa-envelope" style="color: var(--color-primary); margin-right: 10px; width: 15px;"></i>
                        <strong>Email:</strong>
                        <a href="mailto:{{ config('welfare.email') }}" style="color: var(--color-primary);">{{ config('welfare.email') }}</a>
                    </p>
                </div>

                <h4 style="margin-bottom: 15px; font-size: 16px; color: var(--color-heading);">Follow Us</h4>
                <div style="display: flex; gap: 10px;">
                    <a href="https://web.facebook.com/profile.php?id=61590435118262" target="_blank" rel="noopener noreferrer" style="width: 36px; height: 36px; border: 1px solid var(--color-border); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-text); transition: all var(--transition);" onmouseover="this.style.background='var(--color-primary)';this.style.color='white';this.style.borderColor='var(--color-primary)';" onmouseout="this.style.background='none';this.style.color='var(--color-text)';this.style.borderColor='var(--color-border)';" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/mukmin.malaysia" target="_blank" rel="noopener noreferrer" style="width: 36px; height: 36px; border: 1px solid var(--color-border); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-text); transition: all var(--transition);" onmouseover="this.style.background='var(--color-primary)';this.style.color='white';this.style.borderColor='var(--color-primary)';" onmouseout="this.style.background='none';this.style.color='var(--color-text)';this.style.borderColor='var(--color-border)';" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/in/mukminofficial/" target="_blank" rel="noopener noreferrer" style="width: 36px; height: 36px; border: 1px solid var(--color-border); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-text); transition: all var(--transition);" onmouseover="this.style.background='var(--color-primary)';this.style.color='white';this.style.borderColor='var(--color-primary)';" onmouseout="this.style.background='none';this.style.color='var(--color-text)';this.style.borderColor='var(--color-border)';" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://www.tiktok.com/@mukminnasional?is_from_webapp=1&sender_device=pc" target="_blank" rel="noopener noreferrer" style="width: 36px; height: 36px; border: 1px solid var(--color-border); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-text); transition: all var(--transition);" onmouseover="this.style.background='var(--color-primary)';this.style.color='white';this.style.borderColor='var(--color-primary)';" onmouseout="this.style.background='none';this.style.color='var(--color-text)';this.style.borderColor='var(--color-border)';" title="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="https://x.com/Mukminmy" target="_blank" rel="noopener noreferrer" style="width: 36px; height: 36px; border: 1px solid var(--color-border); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-text); transition: all var(--transition);" onmouseover="this.style.background='var(--color-primary)';this.style.color='white';this.style.borderColor='var(--color-primary)';" onmouseout="this.style.background='none';this.style.color='var(--color-text)';this.style.borderColor='var(--color-border)';" title="X / Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="https://youtube.com/@mukmin-i7l?si=ZDB9eyr679HET6Ew" target="_blank" rel="noopener noreferrer" style="width: 36px; height: 36px; border: 1px solid var(--color-border); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-text); transition: all var(--transition);" onmouseover="this.style.background='var(--color-primary)';this.style.color='white';this.style.borderColor='var(--color-primary)';" onmouseout="this.style.background='none';this.style.color='var(--color-text)';this.style.borderColor='var(--color-border)';" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div>
                <h3 style="margin-bottom: 20px; font-size: 20px; color: var(--color-heading);">Send a Message</h3>
                <form method="POST" action="{{ route('welfare.contact.submit') }}" style="display: flex; flex-direction: column; gap: 15px;">
                    @csrf
                    <div>
                        <label style="display: block; font-weight: 500; margin-bottom: 5px; color: var(--color-heading);">Full Name</label>
                        <input type="text" name="name" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 4px; outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--color-primary)';" onblur="this.style.borderColor='var(--color-border)';" required>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 500; margin-bottom: 5px; color: var(--color-heading);">Email Address</label>
                        <input type="email" name="email" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 4px; outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--color-primary)';" onblur="this.style.borderColor='var(--color-border)';" required>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 500; margin-bottom: 5px; color: var(--color-heading);">Your Contact Number</label>
                        <input type="tel" name="phone" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 4px; outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--color-primary)';" onblur="this.style.borderColor='var(--color-border)';" required>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 500; margin-bottom: 5px; color: var(--color-heading);">Message</label>
                        <textarea name="message" rows="5" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 4px; outline: none; transition: border-color 0.3s; font-family: inherit;" onfocus="this.style.borderColor='var(--color-primary)';" onblur="this.style.borderColor='var(--color-border)';" required></textarea>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" style="border: none; border-radius: 4px; font-size: 15px; width: 100%; line-height: 20px; padding: 12px 28px;">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
@media (max-width: 768px) {
    .section-contact > .container > div {
        grid-template-columns: 1fr !important;
        gap: 30px !important;
    }
}
</style>
@endsection
