@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/quran-hadith-styles.css') }}">
@endpush

@section('content')
<div class="quran-container">
    <!-- Header -->
    <header class="quran-header">
        <h1 class="quran-title">📖 Quran Reader</h1>
        <p class="quran-subtitle">Read and explore the Holy Quran</p>
    </header>

    <!-- Verse of the Day -->
    <section class="verse-of-day-section">
        <div class="verse-card verse-of-day-card">
            <div class="card-header">
                <h3>🌟 Verse of the Day</h3>
            </div>
            <div class="card-body" id="verseOfDayContent">
                <div class="loading">Loading verse...</div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="search-section">
        <div class="search-container">
            <h3 class="search-title">🔍 Search Quran</h3>
            <div class="search-box">
                <input 
                    type="text" 
                    id="searchInput" 
                    class="search-input" 
                    placeholder="Search verses by keyword..."
                    minlength="3">
                <button id="searchBtn" class="btn btn-primary">
                    <span class="btn-icon">🔍</span> Search
                </button>
            </div>
            <div id="searchResults" class="search-results"></div>
        </div>
    </section>

    <!-- Surah List -->
    <section class="surah-list-section">
        <h2 class="section-title">📚 Surahs (Chapters)</h2>
        <div class="surah-grid">
            @foreach($surahs as $surah)
            <div class="surah-card" onclick="loadSurah({{ $surah['number'] }})">
                <div class="surah-number">{{ $surah['number'] }}</div>
                <div class="surah-info">
                    <h3 class="surah-name">{{ $surah['name'] }}</h3>
                    <p class="surah-translation">{{ $surah['translation'] }}</p>
                    <div class="surah-meta">
                        <span class="surah-verses">{{ $surah['verses'] }} verses</span>
                        <span class="surah-revelation">{{ $surah['revelation'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Surah Reader Modal -->
    <div id="surahModal" class="modal">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2 id="surahModalTitle" class="modal-title">Surah</h2>
                <button class="modal-close" onclick="closeSurahModal()">✕</button>
            </div>
            <div class="modal-body" id="surahContent">
                <div class="loading">Loading surah...</div>
            </div>
        </div>
    </div>
</div>

<script>
    // State
    let currentLanguage = 'en';

    // Load verse of the day on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadVerseOfDay();
    });

    // Load verse of the day
    async function loadVerseOfDay() {
        try {
            const response = await fetch('{{ route("quran.verse-of-day") }}');
            const data = await response.json();
            
            if (data.success) {
                const verse = data.verse;
                const surahInfo = data.surah_info;
                
                document.getElementById('verseOfDayContent').innerHTML = `
                    <div class="verse-content">
                        <div class="verse-arabic">${verse.arabic_text || ''}</div>
                        <div class="verse-translation">${verse.translation || ''}</div>
                        <div class="verse-reference">
                            <strong>${surahInfo?.name || `Surah ${verse.surah}`}</strong> 
                            (${verse.surah}:${verse.ayah})
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('verseOfDayContent').innerHTML = `
                    <p class="error">No verse available today</p>
                `;
            }
        } catch (error) {
            console.error('Error loading verse of day:', error);
            document.getElementById('verseOfDayContent').innerHTML = `
                <p class="error">Failed to load verse</p>
            `;
        }
    }

    // Search verses
    document.getElementById('searchBtn').addEventListener('click', searchVerses);
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchVerses();
        }
    });

    async function searchVerses() {
        const query = document.getElementById('searchInput').value.trim();
        
        if (query.length < 3) {
            alert('Please enter at least 3 characters');
            return;
        }

        const resultsContainer = document.getElementById('searchResults');
        resultsContainer.innerHTML = '<div class="loading">Searching...</div>';
        resultsContainer.style.display = 'block';

        try {
            const response = await fetch(`{{ route("quran.search") }}?query=${encodeURIComponent(query)}&language=${currentLanguage}`);
            const data = await response.json();
            
            if (data.success && data.results.length > 0) {
                let html = `<h4 class="search-results-title">Found ${data.count} results:</h4>`;
                
                data.results.forEach(verse => {
                    html += `
                        <div class="verse-card search-result-card">
                            <div class="verse-content">
                                <div class="verse-arabic">${verse.arabic_text || ''}</div>
                                <div class="verse-translation">${verse.translation || ''}</div>
                                <div class="verse-reference">
                                    <strong>Surah ${verse.surah}</strong>, Verse ${verse.ayah}
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                resultsContainer.innerHTML = html;
            } else {
                resultsContainer.innerHTML = '<p class="no-results">No results found</p>';
            }
        } catch (error) {
            console.error('Error searching:', error);
            resultsContainer.innerHTML = '<p class="error">Search failed. Please try again.</p>';
        }
    }

    // Load surah
    async function loadSurah(surahNumber) {
        document.getElementById('surahModal').classList.add('active');
        document.getElementById('surahContent').innerHTML = '<div class="loading">Loading surah...</div>';
        
        try {
            const response = await fetch(`/quran/surah/${surahNumber}?language=${currentLanguage}`);
            const data = await response.json();
            
            if (data.success) {
                const surah = data.surah;
                const verses = data.verses;
                
                document.getElementById('surahModalTitle').textContent = 
                    `${surah.name} (${surah.translation})`;
                
                if (verses.length === 0) {
                    document.getElementById('surahContent').innerHTML = `
                        <div class="info-message">
                            <p>📥 This surah is not yet available in the database.</p>
                            <p>We're working on adding all Quran verses soon!</p>
                            <p><strong>Surah Info:</strong></p>
                            <ul>
                                <li>Number: ${surah.number}</li>
                                <li>Name: ${surah.name}</li>
                                <li>Translation: ${surah.translation}</li>
                                <li>Verses: ${surah.verses}</li>
                                <li>Revelation: ${surah.revelation}</li>
                            </ul>
                        </div>
                    `;
                } else {
                    let html = '<div class="verses-container">';
                    verses.forEach(verse => {
                        html += `
                            <div class="verse-card">
                                <div class="verse-number-badge">${verse.ayah}</div>
                                <div class="verse-content">
                                    <div class="verse-arabic">${verse.arabic_text || ''}</div>
                                    ${verse.translation ? `<div class="verse-translation">${verse.translation}</div>` : ''}
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    document.getElementById('surahContent').innerHTML = html;
                }
            } else {
                document.getElementById('surahContent').innerHTML = 
                    '<p class="error">Failed to load surah</p>';
            }
        } catch (error) {
            console.error('Error loading surah:', error);
            document.getElementById('surahContent').innerHTML = 
                '<p class="error">Failed to load surah</p>';
        }
    }

    // Close surah modal
    function closeSurahModal() {
        document.getElementById('surahModal').classList.remove('active');
    }

    // Close modal when clicking outside
    document.getElementById('surahModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSurahModal();
        }
    });
</script>
@endsection
