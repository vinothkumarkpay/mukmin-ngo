<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon - {{ config('welfare.org_name') }}</title>
    <meta name="description" content="Pertubuhan Gabungan MUKMIN Nasional — A national ecosystem for community transformation. Our website is launching soon.">
    <meta name="robots" content="noindex, nofollow">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --color-primary: #d43c18;
            --color-primary-dk: #b83210;
            --color-green: #0c5930;
            --color-heading: #1e1e1e;
            --color-text: #5f6b7a;
            --color-muted: #8a96a3;
            --font-main: 'Roboto', Arial, Helvetica, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: var(--font-main);
            color: var(--color-text);
            background:
                radial-gradient(circle at top right, rgba(212, 60, 24, 0.08), transparent 28%),
                radial-gradient(circle at bottom left, rgba(12, 89, 48, 0.08), transparent 32%),
                linear-gradient(180deg, #f7f9f8 0%, #ffffff 45%, #f4f6f8 100%);
        }

        .coming-soon-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .coming-soon-card {
            width: 100%;
            max-width: 720px;
            background: #ffffff;
            border: 1px solid #e8ecef;
            border-radius: 16px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            text-align: center;
        }

        .coming-soon-logo-wrap {
            background: #ffffff;
            padding: 36px 32px 28px;
            border-bottom: 1px solid #edf2f7;
        }

        .coming-soon-logo {
            max-width: 240px;
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .coming-soon-accent {
            height: 4px;
            background: linear-gradient(90deg, var(--color-green) 0%, #0a4526 100%);
            border-bottom: 2px solid #b3913b;
        }

        .coming-soon-body {
            padding: 36px 36px 34px;
        }

        .coming-soon-badge {
            display: inline-block;
            padding: 7px 14px;
            border-radius: 999px;
            background: rgba(212, 60, 24, 0.1);
            color: var(--color-primary);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .coming-soon-title {
            margin: 0 0 14px;
            font-size: clamp(28px, 4vw, 36px);
            line-height: 1.25;
            color: var(--color-heading);
            font-weight: 700;
        }

        .coming-soon-subtitle {
            margin: 0 auto 22px;
            max-width: 560px;
            font-size: 17px;
            line-height: 1.7;
            color: #4a5568;
            font-weight: 500;
        }

        .coming-soon-message {
            margin: 0 auto 28px;
            max-width: 580px;
            font-size: 15px;
            line-height: 1.8;
            color: var(--color-text);
        }

        .coming-soon-contact {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            border-radius: 8px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            color: var(--color-heading);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .coming-soon-contact:hover {
            border-color: rgba(12, 89, 48, 0.35);
            box-shadow: 0 8px 24px rgba(12, 89, 48, 0.08);
        }

        .coming-soon-contact i {
            color: var(--color-green);
        }

        .coming-soon-social {
            display: flex;
            justify-content: center;
            gap: 14px;
            margin-top: 28px;
        }

        .coming-soon-social a {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: var(--color-green);
            text-decoration: none;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .coming-soon-social a:hover {
            background: var(--color-primary);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .coming-soon-footer {
            padding: 18px 24px 24px;
            border-top: 1px solid #edf2f7;
            font-size: 13px;
            line-height: 1.6;
            color: var(--color-muted);
        }

        @media (max-width: 600px) {
            .coming-soon-body {
                padding: 32px 22px 28px;
            }

            .coming-soon-logo-wrap {
                padding: 28px 20px 22px;
            }

            .coming-soon-contact {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <main class="coming-soon-page">
        <div class="coming-soon-card">
            <div class="coming-soon-logo-wrap">
                <img
                    class="coming-soon-logo"
                    src="{{ asset('welfare/img/mukmin_logo.png') }}"
                    alt="MUKMIN Logo"
                >
            </div>
            <div class="coming-soon-accent" aria-hidden="true"></div>

            <div class="coming-soon-body">
                <span class="coming-soon-badge">Coming Soon</span>
                <h1 class="coming-soon-title">A National Ecosystem for Community Transformation</h1>
                <p class="coming-soon-subtitle">
                    Uniting communities, organisations, and partners to drive inclusive and sustainable development across Malaysia.
                </p>
                <p class="coming-soon-message">
                    Our official website is currently under development as we prepare a platform to share MUKMIN’s mission,
                    programmes, and opportunities to serve together. We appreciate your patience and look forward to welcoming you soon.
                </p>

                <a class="coming-soon-contact" href="mailto:{{ config('welfare.email') }}">
                    <i class="fas fa-envelope" aria-hidden="true"></i>
                    <span>{{ config('welfare.email') }}</span>
                </a>

                <div class="coming-soon-social">
                    <a href="https://web.facebook.com/profile.php?id=61590435118262" title="Facebook" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/mukmin.malaysia" title="Instagram" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/in/mukminofficial/" title="LinkedIn" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://x.com/Mukminmy" title="X / Twitter" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.tiktok.com/@mukminnasional?is_from_webapp=1&sender_device=pc" title="TikTok" target="_blank" rel="noopener noreferrer"><i class="fab fa-tiktok"></i></a>
                    <a href="https://youtube.com/@mukmin-i7l?si=ZDB9eyr679HET6Ew" title="YouTube" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="coming-soon-footer">
                &copy; {{ date('Y') }} {{ config('welfare.org_name') }}
            </div>
        </div>
    </main>
</body>
</html>
