<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PrayerTime;

echo "=== CHECKING PRAYER TIMES TABLE ===\n\n";

$count = PrayerTime::count();
echo "Total prayer time records: $count\n\n";

if ($count > 0) {
    $sample = PrayerTime::where('location_id', 1)->first();
    if ($sample) {
        echo "Sample record for Location 1:\n";
        echo "Date: {$sample->prayer_date}\n";
        echo "Fajr: {$sample->fajr}\n";
        echo "Maghrib: {$sample->maghrib}\n";
        echo "Source: {$sample->calculation_source}\n\n";
        
        echo "THESE TIMES LOOK WRONG!\n";
        echo "Let's delete all prayer times and recalculate them.\n\n";
        echo "Run this command to clear and regenerate:\n";
        echo "php artisan db:seed --class=PrayerTimeSeeder\n";
    }
} else {
    echo "No prayer times in database. Need to seed the database.\n";
}
