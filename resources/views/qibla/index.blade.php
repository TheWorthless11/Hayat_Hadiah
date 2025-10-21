<x-app-layout>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/qibla-google-style.css') }}">
@endpush

@section('content')
    <div class="container">
        <!-- Theme Toggle Button -->
        <button onclick="toggleTheme()" class="theme-btn-floating">
            <span id="themeIcon">🌙</span>
        </button>

        <!-- Page Header -->
        <div class="qibla-header">
            <h1 class="qibla-title">🧭 Qibla Compass</h1>
            <p class="qibla-subtitle">Find the direction to the Holy Kaaba in Mecca</p>
        </div>

        <!-- Main Compass Section -->
        <div class="compass-section">
            <!-- Animated Qibla Icon Container - Google-Inspired -->
            <div class="intro-animation">
                <div class="pin-animation">
                    <!-- Pin Glow Effect -->
                    <div class="pin-glow"></div>
                    
                    <!-- Landing Move Animation -->
                    <div class="landing-move">
                        <!-- Landing Squeeze Animation -->
                        <div class="landing-squeeze">
                            <!-- Welcome Animation Container -->
                            <div class="welcome-animation">
                                <!-- Hider for Moon/Circle Phase -->
                                <div class="hider">
                                    <div class="moon-wrapper">
                                        <!-- Compass Marks (N, E, S, W) -->
                                        <div class="compass-marks">
                                            <svg class="marks-svg" viewBox="0 0 200 200">
                                                <!-- North (Red) -->
                                                <line x1="100" y1="20" x2="100" y2="45" stroke="#dc2626" stroke-width="3" stroke-linecap="round"/>
                                                <text x="100" y="15" text-anchor="middle" fill="#dc2626" font-weight="bold" font-size="16">N</text>
                                                
                                                <!-- East -->
                                                <line x1="155" y1="100" x2="180" y2="100" stroke="#0f766e" stroke-width="2" stroke-linecap="round"/>
                                                <text x="185" y="105" text-anchor="start" fill="#0f766e" font-weight="bold" font-size="14">E</text>
                                                
                                                <!-- South -->
                                                <line x1="100" y1="155" x2="100" y2="180" stroke="#0f766e" stroke-width="2" stroke-linecap="round"/>
                                                <text x="100" y="195" text-anchor="middle" fill="#0f766e" font-weight="bold" font-size="14">S</text>
                                                
                                                <!-- West -->
                                                <line x1="20" y1="100" x2="45" y2="100" stroke="#0f766e" stroke-width="2" stroke-linecap="round"/>
                                                <text x="15" y="105" text-anchor="end" fill="#0f766e" font-weight="bold" font-size="14">W</text>
                                            </svg>
                                        </div>
                                        
                                        <!-- Moon/Circle Phase -->
                                        <div class="moon"></div>
                                    </div>
                                </div>
                                
                                <!-- Spin Container for Compass and Pin -->
                                <div class="spin-container">
                                    <div class="spin-wrapper">
                                        <!-- Compass Phase -->
                                        <div class="compass-container">
                                            <div class="compass-element">
                                                <svg class="compass-image" viewBox="0 0 200 200">
                                                    <!-- Compass Circle -->
                                                    <circle cx="100" cy="100" r="85" fill="none" stroke="#0f766e" stroke-width="3"/>
                                                    <circle cx="100" cy="100" r="75" fill="rgba(240, 253, 250, 0.8)"/>
                                                    
                                                    <!-- Compass Needle -->
                                                    <g class="needle-group">
                                                        <!-- Red North Pointer -->
                                                        <polygon points="100,30 95,100 105,100" fill="#dc2626"/>
                                                        <!-- Gray South Pointer -->
                                                        <polygon points="100,170 95,100 105,100" fill="#64748b"/>
                                                    </g>
                                                    
                                                    <!-- Center Dot -->
                                                    <circle cx="100" cy="100" r="5" fill="#0f766e"/>
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <!-- Pin Phase -->
                                        <svg class="pin-svg" viewBox="0 0 100 140">
                                            <defs>
                                                <linearGradient id="pinGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                                    <stop offset="0%" style="stop-color:#0f766e;stop-opacity:1" />
                                                    <stop offset="100%" style="stop-color:#064e3b;stop-opacity:1" />
                                                </linearGradient>
                                                <filter id="pinShadow">
                                                    <feDropShadow dx="0" dy="4" stdDeviation="8" flood-opacity="0.3"/>
                                                </filter>
                                            </defs>
                                            <!-- Pin Shape -->
                                            <path d="M50 10 C 30 10, 15 25, 15 45 C 15 65, 50 100, 50 100 C 50 100, 85 65, 85 45 C 85 25, 70 10, 50 10 Z" 
                                                  fill="url(#pinGradient)" 
                                                  stroke="#ffffff" 
                                                  stroke-width="3"
                                                  filter="url(#pinShadow)"/>
                                            <!-- Inner Circle for Mecca/Kaaba -->
                                            <circle cx="50" cy="45" r="20" fill="#ffffff" stroke="#0f766e" stroke-width="2"/>
                                        </svg>
                                        
                                        <!-- Mecca/Kaaba Container -->
                                        <div class="mecca-container">
                                            <div class="mecca-shadow"></div>
                                            <div class="mecca-icon">🕋</div>
                                        </div>
                                        
                                        <!-- Qibla Direction Arrow (rotates to point to Kaaba) -->
                                        <div class="qibla-direction-indicator" id="qiblaDirectionRing">
                                            <div class="direction-arrow-modern"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pin Shadow (separate element for realistic effect) -->
                    <div class="pin-shadow-container">
                        <svg class="pin-shadow" viewBox="0 0 120 30">
                            <ellipse cx="60" cy="15" rx="40" ry="8" fill="rgba(0, 0, 0, 0.2)"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Degree Display (always visible) -->
            <div class="degree-display">
                <div class="degree-value" id="degreeValue">
                    @if($qiblaDirection)
                        {{ number_format($qiblaDirection, 1) }}°
                    @else
                        ---°
                    @endif
                </div>
                <div class="degree-label">Qibla Direction</div>
            </div>

            <!-- Location Info Cards -->
            <div class="location-info-grid">
                <!-- Current Location Card -->
                <div class="info-card">
                    <div class="info-card-icon">📍</div>
                    <div class="info-card-title">Your Location</div>
                    <div class="info-card-value" id="currentLocationName">
                        @if($currentLocation)
                            {{ $currentLocation['city'] }}, {{ $currentLocation['country'] }}
                        @else
                            Not detected
                        @endif
                    </div>
                    <div class="info-card-coords" id="currentCoords">
                        @if($currentLocation)
                            {{ number_format($currentLocation['latitude'], 4) }}, {{ number_format($currentLocation['longitude'], 4) }}
                        @else
                            ---, ---
                        @endif
                    </div>
                </div>

                <!-- Distance Card -->
                <div class="info-card">
                    <div class="info-card-icon">📏</div>
                    <div class="info-card-title">Distance to Kaaba</div>
                    <div class="info-card-value" id="distanceValue">
                        @if($distance)
                            {{ number_format($distance, 0) }} km
                        @else
                            --- km
                        @endif
                    </div>
                    <div class="info-card-coords">
                        {{ number_format($distance / 1.60934, 0) ?? '---' }} miles
                    </div>
                </div>

                <!-- Kaaba Location Card -->
                <div class="info-card kaaba-card">
                    <div class="info-card-icon">🕋</div>
                    <div class="info-card-title">Holy Kaaba</div>
                    <div class="info-card-value">Mecca, Saudi Arabia</div>
                    <div class="info-card-coords">21.4225°N, 39.8262°E</div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button type="button" onclick="detectMyLocation()" class="btn-action btn-primary" id="detectBtn">
                <span class="btn-icon">📡</span>
                Detect My Location
            </button>
            
            <button type="button" onclick="showSaveModal()" class="btn-action btn-secondary" id="saveBtn" style="display: none;">
                <span class="btn-icon">💾</span>
                Save This Location
            </button>
            
            <button type="button" onclick="toggleSavedLocations()" class="btn-action btn-outline">
                <span class="btn-icon">⭐</span>
                <span id="savedLocationsCount">{{ $savedLocations ? $savedLocations->count() : 0 }}</span> Saved Locations
            </button>
        </div>

        <!-- Saved Locations Panel (Hidden by default) -->
        <div class="saved-locations-panel" id="savedLocationsPanel" style="display: none;">
            <h3 class="panel-title">📍 Your Saved Locations</h3>
            
            @if($savedLocations && $savedLocations->count() > 0)
                <div class="saved-locations-grid" id="savedLocationsList">
                    @foreach($savedLocations as $location)
                        <div class="saved-location-card" data-id="{{ $location->id }}">
                            <div class="location-header">
                                <h4 class="location-name">{{ $location->location_name }}</h4>
                                <button onclick="toggleFavorite({{ $location->id }})" class="btn-favorite {{ $location->is_favorite ? 'active' : '' }}">
                                    {{ $location->is_favorite ? '⭐' : '☆' }}
                                </button>
                            </div>
                            <div class="location-details">
                                <div class="location-address">{{ $location->city }}, {{ $location->country }}</div>
                                <div class="location-stats">
                                    <span>🧭 {{ number_format($location->qibla_direction, 1) }}°</span>
                                    <span>📏 {{ number_format($location->distance_to_kaaba, 0) }} km</span>
                                    <span>📊 Used {{ $location->usage_count }}x</span>
                                </div>
                            </div>
                            <div class="location-actions">
                                <button onclick="loadSavedLocation({{ $location->id }})" class="btn-load">Load</button>
                                <button onclick="deleteSavedLocation({{ $location->id }})" class="btn-delete">Delete</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <p>No saved locations yet. Detect your location and save it for quick access!</p>
                </div>
            @endif
        </div>

        <!-- Instructions -->
        <div class="instructions-card">
            <h3 class="instructions-title">ℹ️ How to Use</h3>
            <ol class="instructions-list">
                <li>Click <strong>"Detect My Location"</strong> to automatically find your position using GPS</li>
                <li>The compass will show the direction to the Holy Kaaba (0-360 degrees)</li>
                <li>On mobile devices, the compass may use your device's orientation sensor</li>
                <li>Save frequently used locations for quick access</li>
                <li>The green needle points toward the Qibla direction</li>
            </ol>
        </div>
    </div>

    <!-- Save Location Modal -->
    <div class="modal" id="saveModal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>💾 Save Location</h3>
                <button onclick="closeSaveModal()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="saveLocationForm">
                    @csrf
                    <div class="form-group">
                        <label for="location_name">Location Name *</label>
                        <input type="text" id="location_name" name="location_name" class="form-input" placeholder="e.g., Home, Office, Mosque" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address (Optional)</label>
                        <input type="text" id="address" name="address" class="form-input" placeholder="Full address">
                    </div>
                    <input type="hidden" id="save_latitude" name="latitude">
                    <input type="hidden" id="save_longitude" name="longitude">
                    <input type="hidden" id="save_city" name="city">
                    <input type="hidden" id="save_country" name="country">
                    
                    <div class="modal-actions">
                        <button type="button" onclick="closeSaveModal()" class="btn-cancel">Cancel</button>
                        <button type="submit" class="btn-submit">Save Location</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let currentLatitude = {{ $currentLocation['latitude'] ?? 'null' }};
        let currentLongitude = {{ $currentLocation['longitude'] ?? 'null' }};
        let currentQiblaDirection = {{ $qiblaDirection ?? 'null' }};
        let deviceOrientation = null;

        // Theme toggle functionality
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

            // Initialize device orientation if available
            if (window.DeviceOrientationEvent) {
                window.addEventListener('deviceorientation', handleOrientation);
            }

            // Update needle if qibla direction is set
            if (currentQiblaDirection !== null) {
                updateCompass(currentQiblaDirection);
            }
        });

        // Detect user's location
        function detectMyLocation() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser');
                return;
            }

            const btn = document.getElementById('detectBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="btn-icon">⏳</span> Detecting...';

            navigator.geolocation.getCurrentPosition(
                async function(position) {
                    currentLatitude = position.coords.latitude;
                    currentLongitude = position.coords.longitude;
                    
                    // Calculate Qibla direction
                    await calculateQibla(currentLatitude, currentLongitude);
                    
                    // Get location name from reverse geocoding (optional)
                    await getLocationName(currentLatitude, currentLongitude);
                    
                    btn.disabled = false;
                    btn.innerHTML = '<span class="btn-icon">📡</span> Detect My Location';
                    
                    // Show save button
                    document.getElementById('saveBtn').style.display = 'inline-flex';
                },
                function(error) {
                    btn.disabled = false;
                    btn.innerHTML = '<span class="btn-icon">📡</span> Detect My Location';
                    
                    let message = 'Unable to detect location. ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            message += 'Please allow location access in your browser settings.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message += 'Location information is unavailable.';
                            break;
                        case error.TIMEOUT:
                            message += 'Location request timed out.';
                            break;
                        default:
                            message += 'An unknown error occurred.';
                    }
                    alert(message);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        // Calculate Qibla direction
        async function calculateQibla(lat, lng) {
            try {
                const response = await fetch('{{ route("qibla.calculate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        latitude: lat,
                        longitude: lng
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    currentQiblaDirection = data.qibla_direction;
                    
                    // Update UI
                    document.getElementById('degreeValue').textContent = data.qibla_direction.toFixed(1) + '°';
                    document.getElementById('distanceValue').textContent = Math.round(data.distance_km) + ' km';
                    document.getElementById('currentCoords').textContent = lat.toFixed(4) + ', ' + lng.toFixed(4);
                    
                    // Update compass needle
                    updateCompass(data.qibla_direction);
                }
            } catch (error) {
                console.error('Error calculating Qibla:', error);
                alert('Failed to calculate Qibla direction. Please try again.');
            }
        }

        // Update compass direction arrow rotation
        function updateCompass(qiblaDirection) {
            const directionRing = document.getElementById('qiblaDirectionRing');
            if (directionRing) {
                directionRing.style.transform = `translateX(-50%) rotate(${qiblaDirection}deg)`;
            }
        }

        // Get location name from coordinates (simplified - you can integrate with geocoding API)
        async function getLocationName(lat, lng) {
            // Update coordinates display
            document.getElementById('currentCoords').textContent = lat.toFixed(4) + ', ' + lng.toFixed(4);
            document.getElementById('currentLocationName').textContent = 'Current Location';
        }

        // Handle device orientation for mobile compass
        function handleOrientation(event) {
            if (event.alpha !== null && currentQiblaDirection !== null) {
                // Alpha is the compass heading in degrees
                deviceOrientation = event.alpha;
                
                // Adjust direction arrow based on device orientation
                const adjustedDirection = currentQiblaDirection - deviceOrientation;
                const directionRing = document.getElementById('qiblaDirectionRing');
                if (directionRing) {
                    directionRing.style.transform = `translateX(-50%) rotate(${adjustedDirection}deg)`;
                }
            }
        }

        // Show save location modal
        function showSaveModal() {
            if (currentLatitude === null || currentLongitude === null) {
                alert('Please detect your location first');
                return;
            }

            document.getElementById('save_latitude').value = currentLatitude;
            document.getElementById('save_longitude').value = currentLongitude;
            document.getElementById('saveModal').style.display = 'flex';
        }

        // Close save modal
        function closeSaveModal() {
            document.getElementById('saveModal').style.display = 'none';
            document.getElementById('saveLocationForm').reset();
        }

        // Submit save location form
        document.getElementById('saveLocationForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('{{ route("qibla.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    alert('Location saved successfully!');
                    closeSaveModal();
                    location.reload(); // Reload to show new saved location
                } else {
                    alert(result.message || 'Failed to save location');
                }
            } catch (error) {
                console.error('Error saving location:', error);
                alert('Failed to save location. Please try again.');
            }
        });

        // Toggle saved locations panel
        function toggleSavedLocations() {
            const panel = document.getElementById('savedLocationsPanel');
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        }

        // Load a saved location
        async function loadSavedLocation(id) {
            try {
                const response = await fetch(`/qibla/load/${id}`);
                const data = await response.json();

                if (data.success) {
                    currentLatitude = data.location.latitude;
                    currentLongitude = data.location.longitude;
                    currentQiblaDirection = data.qibla_direction;

                    // Update UI
                    document.getElementById('currentLocationName').textContent = data.location.location_name;
                    document.getElementById('currentCoords').textContent = 
                        data.location.latitude.toFixed(4) + ', ' + data.location.longitude.toFixed(4);
                    document.getElementById('degreeValue').textContent = data.qibla_direction.toFixed(1) + '°';
                    document.getElementById('distanceValue').textContent = Math.round(data.distance_km) + ' km';

                    // Update compass
                    updateCompass(data.qibla_direction);

                    // Show save button
                    document.getElementById('saveBtn').style.display = 'inline-flex';
                    
                    alert('Location loaded successfully!');
                }
            } catch (error) {
                console.error('Error loading location:', error);
                alert('Failed to load location');
            }
        }

        // Delete saved location
        async function deleteSavedLocation(id) {
            if (!confirm('Are you sure you want to delete this location?')) {
                return;
            }

            try {
                const response = await fetch(`/qibla/saved/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('Location deleted successfully!');
                    location.reload();
                }
            } catch (error) {
                console.error('Error deleting location:', error);
                alert('Failed to delete location');
            }
        }

        // Toggle favorite status
        async function toggleFavorite(id) {
            try {
                const response = await fetch(`/qibla/favorite/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    location.reload();
                }
            } catch (error) {
                console.error('Error toggling favorite:', error);
                alert('Failed to update favorite status');
            }
        }
    </script>
</x-app-layout>
