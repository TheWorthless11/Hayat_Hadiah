<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Services\PrayerService;
use Illuminate\Http\Request;

class PrayerController extends Controller
{
    protected PrayerService $service;

    public function __construct(PrayerService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // Get all available locations for dropdown
        $locations = Location::orderBy('country')->orderBy('city')->get();

        // choose location: user preference -> first location
        $location = null;
        if ($user && $user->location_id) {
            $location = Location::find($user->location_id);
        }

        // Check if location_id is provided in request
        $selectedLocationId = $request->input('location_id');
        if ($selectedLocationId) {
            $location = Location::find($selectedLocationId);
        }

        if (! $location) {
            $location = Location::first();
        }

        $date = $request->query('date', now()->toDateString());

        // allow dynamic coordinates via query or form: lat, lng, timezone
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $tz = $request->input('timezone');

        if ($lat && $lng && $tz) {
            // If location_id exists, use that location object with calculated times
            if ($selectedLocationId && $location) {
                $times = $this->service->getPrayerTimesForCoordinates(
                    (float)$lat, 
                    (float)$lng, 
                    $date, 
                    $tz, 
                    $request->input('method'),
                    $request->input('school')
                );
                return view('prayers.index', compact('location', 'times', 'date', 'locations'));
            }
            
            // Try to find the nearest city from our database (for auto-detect)
            $nearestLocation = $this->findNearestLocation($locations, (float)$lat, (float)$lng);
            
            if ($nearestLocation) {
                // Use the nearest city
                $location = $nearestLocation;
                $times = $this->service->getPrayerTimesForCoordinates(
                    (float)$lat, 
                    (float)$lng, 
                    $date, 
                    $tz, 
                    $request->input('method'),
                    $request->input('school')
                );
                return view('prayers.index', compact('location', 'times', 'date', 'locations'));
            }
            
            // Otherwise, create a temporary location object for coordinates
            $times = $this->service->getPrayerTimesForCoordinates(
                (float)$lat, 
                (float)$lng, 
                $date, 
                $tz, 
                $request->input('method'),
                $request->input('school')
            );
            $location = (object) ['name' => sprintf('Coordinates (%.4f, %.4f)', $lat, $lng), 'latitude' => $lat, 'longitude' => $lng, 'timezone' => $tz];
            return view('prayers.index', compact('location', 'times', 'date', 'locations'));
        }

        if (! $location) {
            return view('prayers.index', ['error' => 'No location or prayer data available.', 'locations' => $locations]);
        }

        $times = $this->service->getPrayerTimesForDate($location, $date);

        return view('prayers.index', compact('location', 'times', 'date', 'locations'));
    }

    /**
     * Find the nearest location from a collection based on latitude and longitude.
     * Uses the Haversine formula to calculate distance.
     */
    private function findNearestLocation($locations, float $lat, float $lng): ?Location
    {
        $nearestLocation = null;
        $minDistance = PHP_FLOAT_MAX;

        foreach ($locations as $location) {
            if (!$location->latitude || !$location->longitude) {
                continue;
            }

            // Calculate distance using Haversine formula
            $distance = $this->calculateDistance(
                $lat,
                $lng,
                $location->latitude,
                $location->longitude
            );

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearestLocation = $location;
            }
        }

        // Return nearest location if within 100km
        return ($minDistance <= 100) ? $nearestLocation : null;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula.
     * Returns distance in kilometers.
     */
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
