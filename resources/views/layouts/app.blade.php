<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Hayat Hadia') }}</title>
    <!-- Google Fonts for Arabic and elegant English -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Base CSS (shared) -->
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <!-- Per-page styles -->
    @stack('styles')
</head>
<body>
    <nav>
        <div class="container">
            <a href="/" class="logo">{{ config('app.name', 'Hayat Hadia') }}</a>
            <div class="nav-links">
                <a href="/prayers">Prayer Times</a>
                <a href="/qibla">Qibla Compass</a>
                <a href="/quran">Quran</a>
                <a href="/hadith">Hadith</a>
                <a href="/fasting">Fasting</a>
                <a href="/mosques">Nearby Mosque</a>
                <a href="/duas">Duas</a>
                <a href="/zakat">Zakat</a>
            </div>
        </div>
    </nav>

    <!-- Theme Toggle Button (Floating - Top Right) -->
    <button onclick="toggleTheme()" class="theme-btn-floating">
        <span id="themeIcon">🌙</span>
    </button>

    <main>
        @yield('content')
    </main>

    <!-- Theme Toggle Script (Shared across all pages) -->
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = localStorage.getItem('theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            if (newTheme === 'dark') {
                html.classList.add('dark');
                document.getElementById('themeIcon').textContent = '☀️';
            } else {
                html.classList.remove('dark');
                document.getElementById('themeIcon').textContent = '🌙';
            }
            
            localStorage.setItem('theme', newTheme);
        }

        // Load saved theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                document.getElementById('themeIcon').textContent = '☀️';
            }
        });
    </script>
</body>
</html>