@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/prayer-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/countdown-timer.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
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
                        // Skip Imsak (pre-Fajr time) - not a mandatory prayer
                        if (strtolower($name) === 'imsak') {
                            continue;
                        }
                        
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

        <!-- Next Prayer Countdown Timer -->
        <div class="next-prayer-timer-container">
            <div class="timer-circle-container">
                <svg class="timer-progress" width="200" height="200" viewBox="0 0 200 200">
                    <circle class="progress-background" cx="100" cy="100" r="90" />
                    <circle class="progress-bar" cx="100" cy="100" r="90" />
                </svg>
                <div class="timer-text">
                    <div class="timer-label">Next Prayer</div>
                    <div id="next-prayer-name" class="next-prayer-name">---</div>
                    <div id="next-prayer-time" class="next-prayer-time">--:-- --</div>
                    <div id="time-left" class="time-left">--:--:--</div>
                </div>
            </div>
        </div>

    </div>

    

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
            // Update countdown timer
            updateCountdown();

            // Update every second for the countdown
            setInterval(updateCountdown, 1000);
            // Update every minute for the main prayer highlight
            setInterval(highlightCurrentPrayer, 60000);
        });

        // Highlight the current prayer based on time
        function highlightCurrentPrayer() {
            const now = new Date();
            const currentTime = now.getHours() * 60 + now.getMinutes(); // Current time in minutes
            
            const prayerGrid = document.querySelector('.prayer-grid');
            const prayerCards = document.querySelectorAll('.prayer-card');
            let currentPrayerCard = null;
            let nextPrayerTime = Infinity;

            // Clear previous state
            prayerCards.forEach(card => card.classList.remove('current'));
            prayerGrid.classList.remove('has-current');

            // Find which prayer is the current one
            prayerCards.forEach(card => {
                const timeStr = card.getAttribute('data-time');
                if (!timeStr) return;

                const [hours, minutes] = timeStr.split(':').map(Number);
                const prayerTimeInMinutes = hours * 60 + minutes;

                if (prayerTimeInMinutes <= currentTime) {
                    if (!currentPrayerCard || prayerTimeInMinutes > nextPrayerTime) {
                        currentPrayerCard = card;
                        nextPrayerTime = prayerTimeInMinutes;
                    }
                }
            });

            // If it's before the first prayer of the day, the last prayer of yesterday is current
            if (!currentPrayerCard && prayerCards.length > 0) {
                currentPrayerCard = prayerCards[prayerCards.length - 1];
            }

            // Apply 'current' class to the card and 'has-current' to the grid
            if (currentPrayerCard) {
                currentPrayerCard.classList.add('current');
                prayerGrid.classList.add('has-current');
            }
        }

        function updateCountdown() {
            const now = new Date();
            const allPrayerCards = document.querySelectorAll('.prayer-card');
            
            const mandatoryPrayers = Array.from(allPrayerCards).filter(card => {
                const prayerName = card.getAttribute('data-prayer').toLowerCase();
                return !['sunrise', 'midnight', 'qiyam'].includes(prayerName);
            });
            
            let nextPrayer = null;
            let nextPrayerTimeObj = null;
            let prevPrayer = null;

            // Find the next upcoming prayer from the mandatory list
            for (let i = 0; i < mandatoryPrayers.length; i++) {
                const card = mandatoryPrayers[i];
                const timeStr = card.getAttribute('data-time');
                if (!timeStr) continue;

                const [hours, minutes] = timeStr.split(':').map(Number);
                const prayerTime = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes);

                if (prayerTime > now) {
                    nextPrayer = card;
                    nextPrayerTimeObj = prayerTime;
                    prevPrayer = i > 0 ? mandatoryPrayers[i - 1] : mandatoryPrayers[mandatoryPrayers.length - 1];
                    break;
                }
            }

            // If all mandatory prayers for today have passed, next is Fajr tomorrow
            if (!nextPrayer) {
                nextPrayer = mandatoryPrayers[0]; // Fajr
                const [h, m] = nextPrayer.getAttribute('data-time').split(':').map(Number);
                nextPrayerTimeObj = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, h, m);
                prevPrayer = mandatoryPrayers[mandatoryPrayers.length - 1]; // Isha
            }
            
            const prevTimeStr = prevPrayer.getAttribute('data-time');
            const [ph, pm] = prevTimeStr.split(':').map(Number);
            let prevPrayerTimeObj = new Date(now.getFullYear(), now.getMonth(), now.getDate(), ph, pm);
            
            // Handle case where previous prayer was yesterday (e.g., current time is after midnight, before Fajr)
            if (prevPrayerTimeObj > nextPrayerTimeObj) {
                 prevPrayerTimeObj.setDate(prevPrayerTimeObj.getDate() - 1);
            }


            // Calculate time difference
            const totalDuration = nextPrayerTimeObj - prevPrayerTimeObj;
            const timeElapsed = now - prevPrayerTimeObj;
            const timeRemaining = nextPrayerTimeObj - now;

            // Calculate progress
            const progress = Math.min((timeElapsed / totalDuration) * 100, 100);

            // Update UI
            const nextPrayerName = nextPrayer.querySelector('.prayer-name').textContent;
            const nextPrayerTime = nextPrayer.querySelector('.prayer-time').textContent;

            document.getElementById('next-prayer-name').textContent = nextPrayerName;
            document.getElementById('next-prayer-time').textContent = nextPrayerTime;

            // Format time remaining
            const hoursLeft = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutesLeft = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            const secondsLeft = Math.floor((timeRemaining % (1000 * 60)) / 1000);

            document.getElementById('time-left').textContent = 
                `${String(hoursLeft).padStart(2, '0')}:${String(minutesLeft).padStart(2, '0')}:${String(secondsLeft).padStart(2, '0')}`;

            // Update progress bar
            const progressBar = document.querySelector('.progress-bar');
            const circleRadius = progressBar.r.baseVal.value;
            const circumference = 2 * Math.PI * circleRadius;
            const offset = circumference - (progress / 100) * circumference;
            progressBar.style.strokeDashoffset = offset;
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
