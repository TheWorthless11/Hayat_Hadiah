<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Location;
use App\Models\PrayerTime;
use App\Services\PrayerService;
use Carbon\Carbon;

echo "=== PRAYER TIME DEBUG ===\n\n";

$location = Location::find(1); // Dhaka
$date = '2025-10-17';

echo "Location: {$location->city}, {$location->country}\n";
echo "Timezone: {$location->timezone}\n";
echo "Date: {$date}\n\n";

// Check if prayer times exist in database
$prayerRecord = PrayerTime::where('location_id', $location->id)
    ->where('prayer_date', $date)
    ->first();

if ($prayerRecord) {
    echo "=== PRAYER TIMES FROM DATABASE ===\n";
    echo "Fajr (raw object): ";
    var_dump($prayerRecord->fajr);
    echo "Fajr timezone: " . $prayerRecord->fajr->timezone->getName() . "\n";
    echo "Fajr formatted: " . $prayerRecord->fajr->format('Y-m-d H:i:s T') . "\n\n";
    
    echo "Maghrib (raw object): ";
    var_dump($prayerRecord->maghrib);
    echo "Maghrib timezone: " . $prayerRecord->maghrib->timezone->getName() . "\n";
    echo "Maghrib formatted: " . $prayerRecord->maghrib->format('Y-m-d H:i:s T') . "\n\n";
}

// Get times from service
$service = new PrayerService();
$times = $service->getPrayerTimesForDate($location, $date);

echo "=== TIMES FROM SERVICE (H:i format) ===\n";
echo "Fajr: " . ($times['fajr'] ?? 'NULL') . "\n";
echo "Maghrib: " . ($times['maghrib'] ?? 'NULL') . "\n\n";

// Test the FastingController logic
echo "=== TESTING FASTING CONTROLLER LOGIC ===\n";
$dateStr = $date;
$timezone = $location->timezone ?? config('app.timezone', 'UTC');

echo "Creating Sehri from: '$dateStr {$times['fajr']}' in timezone: $timezone\n";
$sehriObj = Carbon::createFromFormat('Y-m-d H:i', $dateStr . ' ' . $times['fajr'], $timezone);
echo "Sehri Object: " . $sehriObj->format('Y-m-d H:i:s T') . "\n";
echo "Sehri Display: " . $sehriObj->format('g:i A') . "\n";
echo "Sehri Timezone: " . $sehriObj->timezone->getName() . "\n\n";

echo "Creating Iftar from: '$dateStr {$times['maghrib']}' in timezone: $timezone\n";
$iftarObj = Carbon::createFromFormat('Y-m-d H:i', $dateStr . ' ' . $times['maghrib'], $timezone);
echo "Iftar Object: " . $iftarObj->format('Y-m-d H:i:s T') . "\n";
echo "Iftar Display: " . $iftarObj->format('g:i A') . "\n";
echo "Iftar Timezone: " . $iftarObj->timezone->getName() . "\n\n";

echo "=== WHAT TIMES DO YOU SEE IN THE UI? ===\n";
echo "Please compare with the 'Display' times above.\n";
