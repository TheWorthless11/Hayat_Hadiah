<?php

require __DIR__.'/vendor/autoload.php';

use IslamicNetwork\PrayerTimes\PrayerTimes;

// Test with Dhaka coordinates
$lat = 23.8103;
$lng = 90.4125;
$timezone = 'Asia/Dhaka';
$method = 'MWL';

echo "Testing Islamic Network Prayer Times Library\n";
echo "============================================\n\n";

echo "Coordinates: $lat, $lng\n";
echo "Timezone: $timezone\n";
echo "Method: $method\n\n";

try {
    $dateTime = new DateTime('2025-10-15', new DateTimeZone($timezone));
    
    echo "Date: " . $dateTime->format('Y-m-d') . " (Today)\n\n";
    
    // Test with Standard school (Shafi'i)
    echo "=== STANDARD SCHOOL (Shafi'i, Maliki, Hanbali) ===\n";
    $pt = new PrayerTimes($method, PrayerTimes::SCHOOL_STANDARD);
    $times = $pt->getTimes(
        $dateTime,
        $lat,
        $lng,
        null, // elevation
        PrayerTimes::LATITUDE_ADJUSTMENT_METHOD_ANGLE,
        null, // midnightMode
        PrayerTimes::TIME_FORMAT_24H
    );
    
    echo "Prayer Times:\n";
    echo "-------------\n";
    echo "Fajr: " . ($times[PrayerTimes::FAJR] ?? 'N/A') . "\n";
    echo "Sunrise: " . ($times[PrayerTimes::SUNRISE] ?? 'N/A') . "\n";
    echo "Dhuhr: " . ($times[PrayerTimes::ZHUHR] ?? 'N/A') . "\n";
    echo "Asr: " . ($times[PrayerTimes::ASR] ?? 'N/A') . "\n";
    echo "Maghrib: " . ($times[PrayerTimes::MAGHRIB] ?? 'N/A') . "\n";
    echo "Isha: " . ($times[PrayerTimes::ISHA] ?? 'N/A') . "\n";
    echo "Midnight: " . ($times[PrayerTimes::MIDNIGHT] ?? 'N/A') . "\n";
    echo "Qiyam (Last Third): " . ($times[PrayerTimes::LAST_THIRD] ?? 'N/A') . "\n";
    
    // Test with Hanafi school
    echo "\n=== HANAFI SCHOOL ===\n";
    $pt = new PrayerTimes($method, PrayerTimes::SCHOOL_HANAFI);
    $times = $pt->getTimes(
        $dateTime,
        $lat,
        $lng,
        null, // elevation
        PrayerTimes::LATITUDE_ADJUSTMENT_METHOD_ANGLE,
        null, // midnightMode
        PrayerTimes::TIME_FORMAT_24H
    );
    
    echo "Prayer Times:\n";
    echo "-------------\n";
    echo "Fajr: " . ($times[PrayerTimes::FAJR] ?? 'N/A') . "\n";
    echo "Sunrise: " . ($times[PrayerTimes::SUNRISE] ?? 'N/A') . "\n";
    echo "Dhuhr: " . ($times[PrayerTimes::ZHUHR] ?? 'N/A') . "\n";
    echo "Asr: " . ($times[PrayerTimes::ASR] ?? 'N/A') . "\n";
    echo "Maghrib: " . ($times[PrayerTimes::MAGHRIB] ?? 'N/A') . "\n";
    echo "Isha: " . ($times[PrayerTimes::ISHA] ?? 'N/A') . "\n";
    echo "Midnight: " . ($times[PrayerTimes::MIDNIGHT] ?? 'N/A') . "\n";
    echo "Qiyam (Last Third): " . ($times[PrayerTimes::LAST_THIRD] ?? 'N/A') . "\n";
    
    echo "\nSuccess! Library is working correctly.\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
