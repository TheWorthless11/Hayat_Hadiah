<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Hayat Hadia') }}</title>
    <!-- Base CSS (shared) -->
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <!-- Per-page styles -->
    @stack('styles')
</head>
<body>
    <nav>
        <div class="container">
            <a href="/" class="logo">🕌 {{ config('app.name', 'Hayat Hadia') }}</a>
            <div class="nav-links">
                <a href="/prayers">⏰ Prayer Times</a>
                <a href="/qibla">🧭 Qibla Compass</a>
                <a href="/quran">📖 Quran</a>
                <a href="/hadith">📚 Hadith</a>
                <a href="/fasting">🌙 Fasting</a>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>
</body>
</html>