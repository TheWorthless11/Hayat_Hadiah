@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/quran-hadith-styles.css') }}">
@endpush

@section('content')
<!-- Noble Quran Header Banner (Full Width) -->
<div class="quran-banner">
    <div class="quran-banner-content">
        <h1 class="quran-arabic-title arabic-calligraphy">القرآن الكريم</h1>
        <p class="quran-english-title">THE NOBLE QURAN</p>
        <div class="quran-search-bar">
            <input 
                type="text" 
                id="mainSearchInput" 
                class="quran-search-input" 
                placeholder="Search or ask anything related to the Quran!">
            <button id="mainSearchBtn" class="quran-search-btn">GO</button>
        </div>
    </div>
</div>

<div class="quran-container">
    <!-- Search Results (Hidden by default) -->
    <div id="searchResults" class="search-results"></div>

    <!-- Surah List -->
    <section class="surah-list-section">
        <h2 class="chapters-title">CHAPTERS (SURAHS)</h2>
        <div class="surah-list">
            @foreach($surahs as $surah)
            <div class="surah-item">
                <span class="surah-number-text">{{ $surah['number'] }}.</span>
                <div class="surah-text-group" onclick="loadSurah({{ $surah['number'] }})">
                    <span class="surah-name-text">{{ $surah['name'] }}</span>
                    <span class="surah-translation-text">({{ $surah['translation'] }})</span>
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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Connect main search bar to search functionality
        document.getElementById('mainSearchBtn').addEventListener('click', function() {
            const mainSearchInput = document.getElementById('mainSearchInput');
            if (mainSearchInput.value.trim()) {
                searchVerses(mainSearchInput.value.trim());
            }
        });
        
        document.getElementById('mainSearchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                searchVerses(this.value.trim());
            }
        });
    });

    // Search verses
    async function searchVerses(query) {
        query = query || document.getElementById('mainSearchInput').value.trim();
        
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
