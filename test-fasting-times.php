<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Location;
use App\Services\PrayerService;
use Carbon\Carbon;

echo "=== FASTING TIME DEBUG ===\n\n";

// Get first location
$location = Location::first();
if (!$location) {
    die("No location found in database!\n");
}

echo "Location: {$location->city}, {$location->country}\n";
echo "Latitude: {$location->latitude}\n";
echo "Longitude: {$location->longitude}\n";
echo "Timezone: " . ($location->timezone ?? 'NOT SET') . "\n\n";

// Get prayer times for today
$service = new PrayerService();
$date = '2025-10-17';
$times = $service->getPrayerTimesForDate($location, $date);

echo "Raw Prayer Times from Service:\n";
print_r($times);
echo "\n";

if (!$times || empty($times['fajr']) || empty($times['maghrib'])) {
    die("Prayer times not available!\n");
}

echo "Fajr (raw): {$times['fajr']}\n";
echo "Maghrib (raw): {$times['maghrib']}\n\n";

// Test the parsing logic from FastingController
$dateObj = Carbon::parse($date);

echo "Creating Sehri time from Fajr...\n";
try {
    $sehriObj = Carbon::createFromFormat('H:i', $times['fajr'], $location->timezone ?? 'UTC')
        ->setDate($dateObj->year, $dateObj->month, $dateObj->day);
    echo "Sehri Object: {$sehriObj->format('Y-m-d H:i:s')} (Timezone: {$sehriObj->timezone})\n";
    echo "Sehri Display: {$sehriObj->format('g:i A')}\n\n";
} catch (\Exception $e) {
    echo "ERROR creating Sehri: {$e->getMessage()}\n\n";
}

echo "Creating Iftar time from Maghrib...\n";
try {
    $iftarObj = Carbon::createFromFormat('H:i', $times['maghrib'], $location->timezone ?? 'UTC')
        ->setDate($dateObj->year, $dateObj->month, $dateObj->day);
    echo "Iftar Object: {$iftarObj->format('Y-m-d H:i:s')} (Timezone: {$iftarObj->timezone})\n";
    echo "Iftar Display: {$iftarObj->format('g:i A')}\n\n";
} catch (\Exception $e) {
    echo "ERROR creating Iftar: {$e->getMessage()}\n\n";
}

// Check what the actual times should be
echo "=== WHAT SHOULD THE TIMES BE? ===\n";
echo "Please tell me:\n";
echo "1. What city/location are you using?\n";
echo "2. What should Fajr time be for October 17, 2025?\n";
echo "3. What should Maghrib time be for October 17, 2025?\n";
echo "4. What times are currently showing in the UI (wrong times)?\n";
