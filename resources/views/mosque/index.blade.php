@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mosque-styles.css') }}">
<style>

    #radius:focus {
        outline: none;
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
    }
    .dark #radius {
        background: linear-gradient(135deg, #064e3b, #053b2f);
        color: #5eead4;
        border-color: #5eead4;
    }

</style>
@endpush

@section('content')
<div class="mosque-container">
    <h1>🕌 Nearby Mosques</h1>
    <p class="muted">Find mosques near your current location</p>

    <div class="mosque-card">
        <!-- All Controls in One Line -->
        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
            <button class="btn-primary" id="detectLocationBtn" style="white-space: nowrap;">
                <span class="btn-icon">📍</span> Detect My Location
            </button>
            
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <label style="margin: 0; font-size: 0.9rem; font-weight: 600; white-space: nowrap; color: #0f766e;">Search Radius:</label>
                <select id="radius" style="padding: 0.5rem 0.8rem; border-radius: 0.5rem; border: 2px solid #14b8a6; font-size: 0.9rem; font-weight: 600; background: linear-gradient(135deg, #ffffff, #f0fdfa); color: #0f766e; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(20, 184, 166, 0.2);">
                    <option value="1000">1 km</option>
                    <option value="2000">2 km</option>
                    <option value="5000" selected>5 km</option>
                    <option value="10000">10 km</option>
                    <option value="20000">20 km</option>
                </select>
            </div>
            
            <button class="btn-primary" id="findMosquesBtn" style="white-space: nowrap;">
                <span class="btn-icon">🔍</span> Find Nearby Mosques
            </button>
            
            <div id="locationInfo" style="display: none; margin: 0; padding: 0.5rem 1rem; background: rgba(15, 118, 110, 0.1); border-radius: 0.375rem; font-size: 0.85rem; white-space: nowrap;">
                <strong>Your Location:</strong> 
                <span id="locationText">-</span>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="loading-state" style="display: none;">
            <div class="spinner"></div>
            <p>Locating nearby mosques...</p>
        </div>

        <!-- Error Message -->
        <div id="errorMessage" class="error-message" style="display: none;"></div>

        <!-- Mosques List -->
        <div id="mosquesContainer" class="mosques-list" style="display: none;">
            <h3 class="list-title">Found <span id="mosqueCount">0</span> Mosques Nearby</h3>
            <div id="mosquesList"></div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="empty-state">
            <div class="empty-icon">🕌</div>
            <p>Click "Detect My Location" first, then "Find Nearby Mosques"</p>
            <small class="muted">We'll use your browser's location to find nearby mosques</small>
        </div>
    </div>
</div>

<script>
let userLatitude = null;
let userLongitude = null;

// Detect Location Button
document.getElementById('detectLocationBtn').addEventListener('click', async () => {
    const btn = document.getElementById('detectLocationBtn');
    const loadingState = document.getElementById('loadingState');
    const errorMessage = document.getElementById('errorMessage');
    const locationInfo = document.getElementById('locationInfo');

    // Hide previous messages
    errorMessage.style.display = 'none';
    
    // Show loading
    loadingState.style.display = 'block';
    btn.disabled = true;

    // Get user location
    if (!navigator.geolocation) {
        showError('Geolocation is not supported by your browser');
        loadingState.style.display = 'none';
        btn.disabled = false;
        return;
    }

    navigator.geolocation.getCurrentPosition(
        async (position) => {
            userLatitude = position.coords.latitude;
            userLongitude = position.coords.longitude;

            // Show location
            loadingState.style.display = 'none';
            locationInfo.style.display = 'inline-block';
            document.getElementById('locationText').textContent = 
                `${userLatitude.toFixed(6)}, ${userLongitude.toFixed(6)}`;
            
            btn.disabled = false;
        },
        (error) => {
            let message = 'Unable to retrieve your location';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    message = 'Location permission denied. Please enable location access.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    message = 'Location information unavailable.';
                    break;
                case error.TIMEOUT:
                    message = 'Location request timed out.';
                    break;
            }
            showError(message);
            loadingState.style.display = 'none';
            btn.disabled = false;
        }
    );
});

// Find Mosques Button
document.getElementById('findMosquesBtn').addEventListener('click', async () => {
    if (!userLatitude || !userLongitude) {
        showError('Please detect your location first by clicking "Detect My Location"');
        return;
    }

    const btn = document.getElementById('findMosquesBtn');
    const loadingState = document.getElementById('loadingState');
    const errorMessage = document.getElementById('errorMessage');
    const emptyState = document.getElementById('emptyState');
    const mosquesContainer = document.getElementById('mosquesContainer');
    const radius = document.getElementById('radius').value;

    // Hide previous results
    errorMessage.style.display = 'none';
    emptyState.style.display = 'none';
    mosquesContainer.style.display = 'none';
    
    // Show loading
    loadingState.style.display = 'block';
    btn.disabled = true;

    // Fetch nearby mosques
    try {
        const response = await fetch('/mosques/nearby', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                latitude: userLatitude,
                longitude: userLongitude,
                radius: radius
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Failed to fetch mosques');
        }

        displayMosques(data.mosques);
    } catch (error) {
        showError(error.message);
    } finally {
        loadingState.style.display = 'none';
        btn.disabled = false;
    }
});

function displayMosques(mosques) {
    const container = document.getElementById('mosquesContainer');
    const list = document.getElementById('mosquesList');
    const count = document.getElementById('mosqueCount');

    if (!mosques || mosques.length === 0) {
        showError('No mosques found nearby. Try increasing the search radius.');
        return;
    }

    count.textContent = mosques.length;
    list.innerHTML = '';

    mosques.forEach((mosque, index) => {
        const row = document.createElement('div');
        row.className = `mosque-row ${index % 2 === 0 ? 'even' : 'odd'}`;
        
        const extraInfo = [];
        if (mosque.denomination) extraInfo.push(`📖 ${mosque.denomination}`);
        if (mosque.capacity) extraInfo.push(`👥 Capacity: ${mosque.capacity}`);
        if (mosque.wheelchair === 'yes') extraInfo.push(`♿ Wheelchair accessible`);
        
        const extraInfoHtml = extraInfo.length > 0 
            ? `<p class="mosque-extra">${extraInfo.join(' • ')}</p>`
            : '';

        row.innerHTML = `
            <div class="mosque-info">
                <div class="mosque-header">
                    <h4 class="mosque-name">🕌 ${mosque.name}</h4>
                </div>
                <p class="mosque-address">📍 ${mosque.address}</p>
                ${extraInfoHtml}
                <div class="mosque-meta">
                    <div class="distance">
                        <span class="distance-icon">📏</span>
                        <span class="distance-value">${mosque.distance} km away</span>
                    </div>
                </div>
            </div>
            <div class="mosque-actions">
                <button class="btn-directions" onclick="openDirections(${mosque.latitude}, ${mosque.longitude}, '${escapeHtml(mosque.name)}')">
                    <span class="btn-icon">🧭</span> Directions
                </button>
            </div>
        `;
        
        list.appendChild(row);
    });

    container.style.display = 'block';
}

function openDirections(lat, lng, name) {
    // Use OpenStreetMap (OSM) for directions - Free and no API key required
    const url = `https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route=${userLatitude},${userLongitude};${lat},${lng}#map=15/${lat}/${lng}`;
    window.open(url, '_blank');
}

function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    errorDiv.textContent = '⚠️ ' + message;
    errorDiv.style.display = 'block';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endsection
