<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Hayat Hadia'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400;500,600&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/base.css') }}">
        <link rel="stylesheet" href="{{ asset('css/chatbot-widget.css') }}">
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        
    <div class="min-h-screen flex flex-col bg-gray-100 dark:bg-gray-900">
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
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Copyright Footer -->
            <footer class="footer">
                <p>&copy; <span class="copyright-year"></span> <span class="developer-name">Code by Mahhia</span>. All rights reserved.</p>
            </footer>
        </div>

        <!-- NEW AND IMPROVED THEME TOGGLE SCRIPT -->
        <script>
            // This function handles the logic of switching the theme
            function toggleTheme() {
                const html = document.documentElement;
                const isDarkMode = html.classList.contains('dark');
                
                if (isDarkMode) {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }

            // This runs when the page is loaded
            document.addEventListener('DOMContentLoaded', function() {
                // 1. Check for a saved theme in localStorage and apply it
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                }

                // 2. Find the new theme toggle button by its ID
                const themeToggleButton = document.getElementById('theme-toggle-btn');
                
                // 3. If the button exists, attach a click event listener to it
                if (themeToggleButton) {
                    themeToggleButton.addEventListener('click', toggleTheme);
                }

                // 4. Update the copyright year
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
