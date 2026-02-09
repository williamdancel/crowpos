<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CrowPOS</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
    <div class="glow"></div>

    <div class="wrap">
        <div class="card">
            <div>
                <img src="/images/crowPOS.png" alt="CrowPOS Logo" style="width: 48px; height: 48px;">
            </div>

            <h1>CrowPOS</h1>

            <p class="tagline">
                Simple point of sale for daily business.
            </p>

            @if (Route::has('login'))
                @auth
                    <a class="btn" href="{{ url('/dashboard') }}">Open POS</a>
                @else
                    <a class="btn" href="{{ route('login') }}">Login</a>
                @endauth
            @endif

            <div class="small">
                © {{ date('Y') }} CrowPOS
            </div>
        </div>
    </div>
</body>
</html>
