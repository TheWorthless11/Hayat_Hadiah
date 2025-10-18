<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Location;

echo "=== LOCATION DATA ===\n\n";

$locations = Location::all();

if ($locations->isEmpty()) {
    echo "No locations found! Please create a location first.\n";
    exit;
}

foreach ($locations as $location) {
    echo "ID: {$location->id}\n";
    echo "City: {$location->city}\n";
    echo "Country: {$location->country}\n";
    echo "Latitude: {$location->latitude}\n";
    echo "Longitude: {$location->longitude}\n";
    echo "Timezone: " . ($location->timezone ?? 'NULL (NOT SET!)') . "\n";
    echo "Calculation Method: " . ($location->calculation_method ?? 'NULL') . "\n";
    echo "\n";
}

echo "\n=== TIMEZONE MUST BE SET! ===\n";
echo "Common timezones:\n";
echo "  - Pakistan: Asia/Karachi\n";
echo "  - India: Asia/Kolkata\n";
echo "  - Bangladesh: Asia/Dhaka\n";
echo "  - UAE/Dubai: Asia/Dubai\n";
echo "  - Saudi Arabia: Asia/Riyadh\n";
echo "  - Egypt: Africa/Cairo\n";
echo "  - Turkey: Europe/Istanbul\n";
echo "  - Indonesia: Asia/Jakarta\n";
echo "  - Malaysia: Asia/Kuala_Lumpur\n";
echo "  - UK: Europe/London\n";
echo "  - USA (New York): America/New_York\n\n";

echo "To update timezone, run:\n";
echo "php artisan tinker\n";
echo "Then type:\n";
echo "App\\Models\\Location::find(1)->update(['timezone' => 'Asia/Karachi']);\n";
