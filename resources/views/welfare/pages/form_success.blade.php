@extends('welfare.layouts.app')

@section('title', $title . ' - Pertubuhan Gabungan MUKMIN Nasional')

@section('content')
<section class="section-padding bg-light" style="padding: 100px 0; min-height: calc(100vh - 350px); display: flex; align-items: center; justify-content: center;">
    <div class="container">
        <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 50px 40px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #eaeaea;">
            <div style="width: 80px; height: 80px; background: #fdf6f4; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; color: #d43c18; font-size: 36px; box-shadow: 0 4px 15px rgba(212, 60, 24, 0.15);">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h2 style="font-size: 28px; font-weight: 700; color: #1e1e1e; margin-bottom: 15px;">{{ $title }}</h2>
            <p style="font-size: 15px; line-height: 26px; color: #555; margin-bottom: 35px;">{!! nl2br(e($message)) !!}</p>
            
            <a href="{{ route('welfare.serve') }}" class="btn-donate-rounded" style="padding: 14px 32px; font-size: 15px; text-decoration: none; display: inline-block;">
                Return to Serve Together
            </a>
        </div>
    </div>
</section>
@endsection
