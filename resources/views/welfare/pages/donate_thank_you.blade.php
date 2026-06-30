@extends('welfare.layouts.app')

@section('title', 'Thank You - Pertubuhan Gabungan MUKMIN Nasional')

@section('content')
<section class="section-padding bg-light" style="padding: 100px 0; min-height: calc(100vh - 350px); display: flex; align-items: center; justify-content: center;">
    <div class="container">
        <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 50px 40px; text-align: left; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #eaeaea;">
            <div style="width: 80px; height: 80px; background: #f0fdf4; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; color: #16a34a; font-size: 36px; box-shadow: 0 4px 15px rgba(22, 163, 74, 0.15);">
                <i class="fas fa-check-circle"></i>
            </div>

            <div style="font-size: 15px; line-height: 26px; color: #555; margin-bottom: 35px;">
                <p style="margin: 0 0 20px 0;">Salam from MUKMIN,</p>

                <p style="margin: 0 0 20px 0;">Thank you for your generous contribution to MUKMIN.</p>

                <p style="margin: 0 0 20px 0;">We have successfully received your donation, and your support will help strengthen initiatives that advance education, community welfare, leadership development, economic empowerment, and community-building efforts across the communities we serve.<br>
                At MUKMIN, we believe that meaningful change begins when individuals, organisations and communities come together with a common purpose. Every contribution, regardless of its size, becomes part of a larger movement dedicated to creating sustainable impact and improving lives across our communities.</p>

                <p style="margin: 0 0 20px 0;">Should you have any questions regarding your contribution, please feel free to contact us at <a href="mailto:info@mukmin.org" style="color: #0c5930; text-decoration: none; font-weight: 500;">info@mukmin.org</a>.</p>

                <p style="margin: 0 0 20px 0;">On behalf of the entire MUKMIN family, thank you for believing in our mission and standing with us as we work towards a more empowered, compassionate and united community.</p>

                <p style="margin: 0;">
                    With sincere appreciation,<br>
                    MUKMIN Secretariat<br>
                    Pertubuhan Gabungan MUKMIN Nasional<br>
                    🌐 <a href="https://www.mukmin.org" style="color: #0c5930; text-decoration: none; font-weight: 500;">www.mukmin.org</a>
                </p>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('welfare.home') }}" class="btn-donate-rounded" style="padding: 14px 32px; font-size: 15px; text-decoration: none; display: inline-block;">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
