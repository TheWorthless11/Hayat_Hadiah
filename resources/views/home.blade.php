<x-app-layout>
    {{-- Push the page-specific stylesheet to the layout --}}
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/home-styles.css') }}">
    @endpush

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="greeting-card">
                <h1 class="arabic-greeting">السلام عليكم ورحمة الله وبركاته</h1>
                <p class="greeting-translation">As-salamu alaykum wa rahmatullahi wa barakatuh</p>
                <p class="greeting-subtitle">Peace, mercy, and blessings of Allah be upon you</p>
            </div>

            <div class="hero-grid">
                <!-- Prayer Time Countdown -->
                <div class="hero-card prayer-card">
                    <div class="card-icon">⏰</div>
                    <h2>Next Prayer</h2>
                    <div class="prayer-name">{{ $nextPrayer['name'] }}</div>
                    <div class="prayer-time">{{ $nextPrayer['time'] }}</div>
                    <div class="countdown">
                        <span class="countdown-badge">in {{ $nextPrayer['countdown'] }}</span>
                    </div>
                    <a href="/prayers" class="card-link">View all prayer times →</a>
                </div>

                <!-- Islamic Calendar -->
                <div class="hero-card calendar-card">
                    <div class="card-icon">📅</div>
                    <h2>Today's Date</h2>
                    <div class="hijri-date">{{ $hijriDate }}</div>
                    <div class="gregorian-date">{{ $gregorianDate }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Highlights Section -->
    <div class="highlights-section">
        <div class="container">
            <h2 class="section-title">✨ Daily Spiritual Nourishment</h2>

            <!-- Verse of the Day -->
            @if($verseOfDay)
            <div class="highlight-card verse-card">
                <div class="highlight-header">
                    <div class="highlight-icon">📖</div>
                    <div>
                        <h3>Verse of the Day</h3>
                        <p class="highlight-meta">Surah {{ $verseOfDay->surah_number }}, Ayah {{ $verseOfDay->verse_number }}</p>
                    </div>
                </div>
                <div class="highlight-content">
                    <p class="arabic-text">{{ $verseOfDay->text_arabic }}</p>
                    <p class="translation-text">{{ $verseOfDay->text_english }}</p>
                    @if($verseOfDay->transliteration)
                    <p class="transliteration-text">{{ $verseOfDay->transliteration }}</p>
                    @endif
                </div>
                <a href="/quran/surah/{{ $verseOfDay->surah_number }}" class="highlight-link">Read full Surah →</a>
            </div>
            @endif

            <!-- Hadith of the Day -->
            @if($hadithOfDay)
            <div class="highlight-card hadith-card">
                <div class="highlight-header">
                    <div class="highlight-icon">📚</div>
                    <div>
                        <h3>Hadith of the Day</h3>
                        <p class="highlight-meta">{{ $hadithOfDay->collection }} - Book {{ $hadithOfDay->book_number }}</p>
                    </div>
                </div>
                <div class="highlight-content">
                    <p class="hadith-text">{{ Str::limit($hadithOfDay->text_english, 300) }}</p>
                    @if($hadithOfDay->narrator)
                    <p class="narrator-text">— Narrated by {{ $hadithOfDay->narrator }}</p>
                    @endif
                </div>
                <a href="/hadith/{{ $hadithOfDay->id }}" class="highlight-link">Read more →</a>
            </div>
            @endif

            <!-- Quick Inspiration Banner -->
            <div class="inspiration-banner">
                <div class="inspiration-icon">🌟</div>
                <div class="inspiration-content">
                    <h3>Continue Your Journey</h3>
                    <p>Explore more teachings from the Quran and Hadith, calculate your Zakat, find the Qibla direction, or plan your fasting schedule.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Stats -->
    <div class="footer-stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">114</div>
                    <div class="stat-label">Surahs</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">6,236</div>
                    <div class="stat-label">Verses</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">7</div>
                    <div class="stat-label">Collections</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">∞</div>
                    <div class="stat-label">Blessings</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
