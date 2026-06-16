<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Donate')</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/welfare.css') }}?v={{ filemtime(public_path('css/welfare.css')) }}">
    @stack('styles')
</head>
<body class="donate-demo-page">
    <main class="donate-demo-main">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
