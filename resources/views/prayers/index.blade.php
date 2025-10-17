@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/prayer-styles.css') }}">
@endpush

@section('content')
    <div class="container">
        <!-- Theme Toggle Button (Top Right) -->
        <button onclick="toggleTheme()" class="theme-btn-floating">
            <span id="themeIcon">🌙</span>
        </button>

        <!-- TOP SECTION: 2 Columns -->
        <div class="top-section">
            <!-- LEFT: Location Info -->
            <div class="info-box">
                <div class="info-section">
                    <div class="info-label">Prayer Time in</div>
                    <div class="info-location">
                        @if(isset($location))
                            {{ $location->city }}, {{ $location->country }}
                        @else
                            Select Location
                        @endif
                    </div>
                </div>
                
                <div class="search-section">
                    <select id="citySelector" onchange="selectCity()" class="location-search">
                        <option value="">🔍 Search any location</option>
                        @if(isset($locations))
                            @foreach($locations->groupBy('country') as $country => $cities)
                                <optgroup label="{{ $country }}">
                                    @foreach($cities as $loc)
                                        <option value="{{ $loc->id }}" 
                                                data-lat="{{ $loc->latitude }}" 
                                                data-lng="{{ $loc->longitude }}" 
                                                data-tz="{{ $loc->timezone }}"
                                                data-method="{{ $loc->calculation_method ?? 'MWL' }}"
                                                {{ (isset($location) && isset($location->id) && $location->id == $loc->id) ? 'selected' : '' }}>
                                            {{ $loc->city }}, {{ $loc->country }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        @endif
                    </select>
                    
                    <button type="button" onclick="detectLocation()" class="btn-detect">
                        Auto-Detect Your Location
                    </button>
                </div>
            </div>

            <!-- RIGHT: Date & Settings -->
            <div class="info-box">
                <div class="info-section">
                    <div class="info-date-full">{{ now()->format('F d, Y') }}</div>
                    <div class="info-date">{{ now()->format('l') }}</div>
                </div>

                <form method="get" id="prayerForm" class="settings-form">
                    <input type="hidden" name="lat" value="{{ request('lat', $location->latitude ?? '') }}">
                    <input type="hidden" name="lng" value="{{ request('lng', $location->longitude ?? '') }}">
                    <input type="hidden" name="timezone" value="{{ request('timezone', $location->timezone ?? '') }}">
                    
                    <div class="setting-group">
                        <select name="method" onchange="document.getElementById('prayerForm').submit()">
                            <option value="MWL" {{ request('method') == 'MWL' ? 'selected' : '' }}>Muslim World League</option>
                            <option value="ISNA" {{ request('method') == 'ISNA' ? 'selected' : '' }}>ISNA</option>
                            <option value="EGYPT" {{ request('method') == 'EGYPT' ? 'selected' : '' }}>Egyptian</option>
                            <option value="MAKKAH" {{ request('method') == 'MAKKAH' ? 'selected' : '' }}>Umm Al-Qura</option>
                            <option value="KARACHI" {{ request('method') == 'KARACHI' ? 'selected' : '' }}>Karachi</option>
                        </select>
                    </div>

                    <div class="setting-group">
                        <select name="school" onchange="document.getElementById('prayerForm').submit()">
                            <option value="STANDARD" {{ request('school') == 'STANDARD' ? 'selected' : '' }}>Standard</option>
                            <option value="HANAFI" {{ request('school') == 'HANAFI' ? 'selected' : '' }}>Hanafi</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- MIDDLE: Prayer Times Grid -->
        @if(isset($error))
            <div class="alert alert-error">{{ $error }}</div>
        @elseif(!$times)
            <div class="alert alert-warning">No prayer times available.</div>
        @else
            <div class="prayer-grid">
                @foreach($times as $name => $time)
                    @if(in_array($name, ['calculation_source', 'adjustments']))
                        @continue
                    @endif
                    @php
                        // Convert 24-hour time to 12-hour format with AM/PM
                        $timeObj = \DateTime::createFromFormat('H:i', $time);
                        $time12h = $timeObj ? $timeObj->format('g:i A') : $time;
                    @endphp
                    <div class="prayer-card" data-prayer="{{ $name }}" data-time="{{ $time }}">
                        <div class="prayer-content">
                            <div class="prayer-name">{{ ucfirst($name) }}</div>
                            <div class="prayer-time">{{ $time12h }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Copyright Footer -->
    <footer class="footer">
        <p>&copy; <span class="copyright-year">2025</span> <span class="developer-name">Code by Mahhia</span>. All rights reserved.</p>
    </footer>

    <script>
        // Theme Toggle Functionality
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

            // Highlight current prayer
            highlightCurrentPrayer();
            // Update every minute
            setInterval(highlightCurrentPrayer, 60000);
        });

        // Highlight the current prayer based on time
        function highlightCurrentPrayer() {
            const now = new Date();
            const currentTime = now.getHours() * 60 + now.getMinutes(); // Current time in minutes
            
            const prayerCards = document.querySelectorAll('.prayer-card');
            let currentPrayer = null;
            let nextPrayerTime = Infinity;

            // Convert prayer times to minutes and find current prayer
            prayerCards.forEach(card => {
                const timeStr = card.getAttribute('data-time');
                if (!timeStr) return;

                const [hours, minutes] = timeStr.split(':').map(Number);
                const prayerTimeInMinutes = hours * 60 + minutes;

                // If this prayer time has passed and is closer than the next one
                if (prayerTimeInMinutes <= currentTime) {
                    if (!currentPrayer || prayerTimeInMinutes > nextPrayerTime) {
                        if (currentPrayer) currentPrayer.classList.remove('current');
                        currentPrayer = card;
                        nextPrayerTime = prayerTimeInMinutes;
                    }
                }
            });

            // If no prayer has passed yet, highlight the first one (likely Fajr from previous day logic)
            if (!currentPrayer && prayerCards.length > 0) {
                currentPrayer = prayerCards[prayerCards.length - 1]; // Last prayer (Qiyam or Isha)
            }

            // Apply current class
            prayerCards.forEach(card => card.classList.remove('current'));
            if (currentPrayer) {
                currentPrayer.classList.add('current');
            }
        }

        // Auto-detect location using browser's Geolocation API
        function detectLocation() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser');
                return;
            }

            // Show loading state
            const btn = event.target;
            btn.disabled = true;
            btn.innerHTML = '⏳ Detecting...';

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    // Try to detect timezone (this is a simple approach)
                    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                    
                    // Fill the form
                    document.querySelector('input[name="lat"]').value = lat.toFixed(4);
                    document.querySelector('input[name="lng"]').value = lng.toFixed(4);
                    document.querySelector('input[name="timezone"]').value = timezone;
                    
                    // Submit the form
                    document.getElementById('prayerForm').submit();
                },
                function(error) {
                    btn.disabled = false;
                    btn.innerHTML = '🌍 Auto-Detect Location';
                    
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
                }
            );
        }

        // Select city from dropdown
        function selectCity() {
            const selector = document.getElementById('citySelector');
            const selectedOption = selector.options[selector.selectedIndex];
            
            if (!selectedOption.value) return;
            
            const locationId = selectedOption.value;
            const lat = selectedOption.getAttribute('data-lat');
            const lng = selectedOption.getAttribute('data-lng');
            const tz = selectedOption.getAttribute('data-tz');
            const method = selectedOption.getAttribute('data-method');
            
            // Create hidden input for location_id if it doesn't exist
            let locationIdInput = document.querySelector('input[name="location_id"]');
            if (!locationIdInput) {
                locationIdInput = document.createElement('input');
                locationIdInput.type = 'hidden';
                locationIdInput.name = 'location_id';
                document.getElementById('prayerForm').appendChild(locationIdInput);
            }
            locationIdInput.value = locationId;
            
            // Fill the form
            document.querySelector('input[name="lat"]').value = lat;
            document.querySelector('input[name="lng"]').value = lng;
            document.querySelector('input[name="timezone"]').value = tz;
            document.querySelector('select[name="method"]').value = method;
            
            // Submit the form
            document.getElementById('prayerForm').submit();
        }
    </script>
@endsection
