<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Hayat Hadia'))</title>

        <!-- Your Original Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
        
        <!-- Breeze Font -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400;500,600&display=swap" rel="stylesheet" />

        <!-- Breeze/Vite Styles (Loads Tailwind components) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Your Original CSS Files -->
        <link rel="stylesheet" href="{{ asset('css/base.css') }}">
        <link rel="stylesheet" href="{{ asset('css/chatbot-widget.css') }}">
        
        <!-- Your Page-Specific Styles -->
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        
        <!-- Your Original Theme Toggle Button -->
        <button onclick="toggleTheme()" class="theme-btn-floating">
            <span id="themeIcon">🌙</span>
        </button>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Your Original Copyright Footer -->
            <footer class="footer">
                <p>&copy; <span class="copyright-year"></span> <span class="developer-name">Code by Mahhia</span>. All rights reserved.</p>
            </footer>
        </div>

        <!-- Your Original Theme Toggle Script -->
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

            // Load saved theme on page load and update year
            document.addEventListener('DOMContentLoaded', function() {
                const savedTheme = localStorage.getItem('theme') || 'light';
                if (savedTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                    document.getElementById('themeIcon').textContent = '☀️';
                }
                
                const yearSpan = document.querySelector('.copyright-year');
                if (yearSpan) {
                    yearSpan.textContent = new Date().getFullYear();
                }
            });
        </script>

        <!-- Your Original Chatbot Script -->
        <script src="{{ asset('js/chatbot-widget.js') }}"></script>
        
        <!-- Your Page-Specific Scripts -->
        @stack('scripts')
    </body>
</html>

