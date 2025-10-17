@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/quran-hadith-styles.css') }}">
@endpush

@section('content')
<div class="hadith-container">
    <!-- Header -->
    <header class="hadith-header">
        <h1 class="hadith-title">📚 Hadith Collections</h1>
        <p class="hadith-subtitle">Authentic sayings and teachings of Prophet Muhammad ﷺ</p>
    </header>

    <!-- Hadith of the Day -->
    <section class="hadith-of-day-section">
        <div class="hadith-card hadith-of-day-card">
            <div class="card-header">
                <h3>🌟 Hadith of the Day</h3>
            </div>
            <div class="card-body" id="hadithOfDayContent">
                <div class="loading">Loading hadith...</div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="search-section">
        <div class="search-container">
            <h3 class="search-title">🔍 Search Hadith</h3>
            <div class="search-box">
                <input 
                    type="text" 
                    id="searchInput" 
                    class="search-input" 
                    placeholder="Search hadiths by keyword..."
                    minlength="3">
                <select id="collectionFilter" class="collection-select">
                    <option value="">All Collections</option>
                    @foreach($collections as $collection)
                    <option value="{{ $collection['slug'] }}">{{ $collection['name'] }}</option>
                    @endforeach
                </select>
                <button id="searchBtn" class="btn btn-primary">
                    <span class="btn-icon">🔍</span> Search
                </button>
            </div>
            <div id="searchResults" class="search-results"></div>
        </div>
    </section>

    <!-- Collections Grid -->
    <section class="collections-section">
        <h2 class="section-title">📖 Hadith Collections</h2>
        <div class="collections-grid">
            @foreach($collections as $collection)
            <div class="collection-card" onclick="loadCollection('{{ $collection['slug'] }}')">
                <div class="collection-icon">
                    @if($collection['slug'] === 'bukhari' || $collection['slug'] === 'muslim')
                        ⭐
                    @else
                        📘
                    @endif
                </div>
                <div class="collection-info">
                    <h3 class="collection-name">{{ $collection['name'] }}</h3>
                    <p class="collection-description">{{ $collection['description'] }}</p>
                    <div class="collection-stats">
                        <span class="stat-item">📚 {{ $collection['books'] }} books</span>
                        <span class="stat-item">📝 {{ $collection['hadiths'] }} hadiths</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Collection Reader Modal -->
    <div id="collectionModal" class="modal">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2 id="collectionModalTitle" class="modal-title">Collection</h2>
                <button class="modal-close" onclick="closeCollectionModal()">✕</button>
            </div>
            <div class="modal-body" id="collectionContent">
                <div class="loading">Loading collection...</div>
            </div>
            <div class="modal-footer" id="collectionPagination" style="display: none;">
                <button id="prevPageBtn" class="btn btn-secondary" onclick="loadPreviousPage()">
                    ← Previous
                </button>
                <span id="pageInfo" class="page-info">Page 1 of 1</span>
                <button id="nextPageBtn" class="btn btn-secondary" onclick="loadNextPage()">
                    Next →
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // State
    let currentCollection = null;
    let currentPage = 1;
    let totalPages = 1;

    // Load hadith of the day on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadHadithOfDay();
    });

    // Load hadith of the day
    async function loadHadithOfDay() {
        try {
            const response = await fetch('{{ route("hadith.of-day") }}');
            const data = await response.json();
            
            if (data.success) {
                const hadith = data.hadith;
                
                document.getElementById('hadithOfDayContent').innerHTML = `
                    <div class="hadith-content">
                        <div class="hadith-text">${hadith.text || hadith.translation || ''}</div>
                        <div class="hadith-reference">
                            <strong>${hadith.collection}</strong>
                            ${hadith.narrator ? ` - Narrated by ${hadith.narrator}` : ''}
                            ${hadith.reference ? ` - ${hadith.reference}` : ''}
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('hadithOfDayContent').innerHTML = `
                    <p class="info-message">📥 No hadiths available yet. Please add hadiths to the database.</p>
                `;
            }
        } catch (error) {
            console.error('Error loading hadith of day:', error);
            document.getElementById('hadithOfDayContent').innerHTML = `
                <p class="error">Failed to load hadith</p>
            `;
        }
    }

    // Search hadiths
    document.getElementById('searchBtn').addEventListener('click', searchHadiths);
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchHadiths();
        }
    });

    async function searchHadiths() {
        const query = document.getElementById('searchInput').value.trim();
        const collection = document.getElementById('collectionFilter').value;
        
        if (query.length < 3) {
            alert('Please enter at least 3 characters');
            return;
        }

        const resultsContainer = document.getElementById('searchResults');
        resultsContainer.innerHTML = '<div class="loading">Searching...</div>';
        resultsContainer.style.display = 'block';

        try {
            let url = `{{ route("hadith.search") }}?query=${encodeURIComponent(query)}`;
            if (collection) {
                url += `&collection=${collection}`;
            }
            
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.success && data.results.length > 0) {
                let html = `<h4 class="search-results-title">Found ${data.count} results:</h4>`;
                
                data.results.forEach(hadith => {
                    html += `
                        <div class="hadith-card search-result-card">
                            <div class="hadith-content">
                                <div class="hadith-text">${hadith.text || hadith.translation || ''}</div>
                                <div class="hadith-reference">
                                    <strong>${hadith.collection}</strong>
                                    ${hadith.narrator ? ` - ${hadith.narrator}` : ''}
                                    ${hadith.reference ? ` - ${hadith.reference}` : ''}
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

    // Load collection
    async function loadCollection(collection, page = 1) {
        currentCollection = collection;
        currentPage = page;
        
        document.getElementById('collectionModal').classList.add('active');
        document.getElementById('collectionContent').innerHTML = '<div class="loading">Loading collection...</div>';
        document.getElementById('collectionPagination').style.display = 'none';
        
        try {
            const response = await fetch(`/hadith/collection/${collection}?page=${page}&per_page=10`);
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('collectionModalTitle').textContent = 
                    data.collection.charAt(0).toUpperCase() + data.collection.slice(1) + ' Collection';
                
                if (data.hadiths.length === 0) {
                    document.getElementById('collectionContent').innerHTML = `
                        <div class="info-message">
                            <p>📥 This collection is not yet available in the database.</p>
                            <p>We're working on adding all hadith collections soon!</p>
                        </div>
                    `;
                } else {
                    let html = '<div class="hadiths-container">';
                    data.hadiths.forEach(hadith => {
                        html += `
                            <div class="hadith-card">
                                <div class="hadith-content">
                                    <div class="hadith-text">${hadith.text || hadith.translation || ''}</div>
                                    ${hadith.narrator ? `<div class="hadith-narrator">Narrated by: ${hadith.narrator}</div>` : ''}
                                    <div class="hadith-reference">
                                        ${hadith.book ? `Book: ${hadith.book}` : ''}
                                        ${hadith.reference ? ` - ${hadith.reference}` : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    document.getElementById('collectionContent').innerHTML = html;
                    
                    // Update pagination
                    totalPages = data.pagination.total_pages;
                    document.getElementById('pageInfo').textContent = 
                        `Page ${data.pagination.current_page} of ${totalPages}`;
                    document.getElementById('prevPageBtn').disabled = currentPage === 1;
                    document.getElementById('nextPageBtn').disabled = currentPage === totalPages;
                    document.getElementById('collectionPagination').style.display = 'flex';
                }
            } else {
                document.getElementById('collectionContent').innerHTML = 
                    '<p class="error">Failed to load collection</p>';
            }
        } catch (error) {
            console.error('Error loading collection:', error);
            document.getElementById('collectionContent').innerHTML = 
                '<p class="error">Failed to load collection</p>';
        }
    }

    // Pagination
    function loadPreviousPage() {
        if (currentPage > 1) {
            loadCollection(currentCollection, currentPage - 1);
        }
    }

    function loadNextPage() {
        if (currentPage < totalPages) {
            loadCollection(currentCollection, currentPage + 1);
        }
    }

    // Close collection modal
    function closeCollectionModal() {
        document.getElementById('collectionModal').classList.remove('active');
    }

    // Close modal when clicking outside
    document.getElementById('collectionModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCollectionModal();
        }
    });
</script>
@endsection
