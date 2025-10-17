<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KaabaLocation;
use App\Models\SavedQiblaLocation;
use App\Models\QiblaCompassLog;
use Illuminate\Support\Facades\Auth;

class QiblaController extends Controller
{
    /**
     * Display the Qibla compass interface
     */
    public function index(Request $request)
    {
        $kaaba = KaabaLocation::getKaaba();
        
        // Get user's saved locations if authenticated
        $savedLocations = null;
        $favoriteLocations = null;
        
        if (Auth::check()) {
            $savedLocations = SavedQiblaLocation::where('user_id', Auth::id())
                ->orderBy('usage_count', 'desc')
                ->get();
            
            $favoriteLocations = SavedQiblaLocation::where('user_id', Auth::id())
                ->where('is_favorite', true)
                ->orderBy('usage_count', 'desc')
                ->get();
        }
        
        // Get initial calculation if coordinates are provided
        $qiblaDirection = null;
        $distance = null;
        $currentLocation = null;
        
        if ($request->has('lat') && $request->has('lng')) {
            $lat = $request->input('lat');
            $lng = $request->input('lng');
            
            $qiblaDirection = KaabaLocation::calculateQiblaDirection($lat, $lng);
            $distance = KaabaLocation::calculateDistanceToKaaba($lat, $lng);
            
            $currentLocation = [
                'latitude' => $lat,
                'longitude' => $lng,
                'city' => $request->input('city', 'Unknown'),
                'country' => $request->input('country', 'Unknown'),
            ];
            
            // Log the compass usage
            $this->logCompassUsage($request, $lat, $lng, $qiblaDirection);
        }
        
        return view('qibla.index', compact(
            'kaaba',
            'savedLocations',
            'favoriteLocations',
            'qiblaDirection',
            'distance',
            'currentLocation'
        ));
    }

    /**
     * Calculate Qibla direction from coordinates (AJAX endpoint)
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        $qiblaDirection = KaabaLocation::calculateQiblaDirection($lat, $lng);
        $distance = KaabaLocation::calculateDistanceToKaaba($lat, $lng);

        // Log the compass usage
        $this->logCompassUsage($request, $lat, $lng, $qiblaDirection);

        return response()->json([
            'success' => true,
            'qibla_direction' => $qiblaDirection,
            'distance_km' => $distance,
            'kaaba' => [
                'latitude' => 21.4225,
                'longitude' => 39.8262,
            ],
        ]);
    }

    /**
     * Save a location for quick access
     */
    public function saveLocation(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to save locations',
            ], 401);
        }

        $request->validate([
            'location_name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $qiblaDirection = KaabaLocation::calculateQiblaDirection(
            $request->input('latitude'),
            $request->input('longitude')
        );

        $distance = KaabaLocation::calculateDistanceToKaaba(
            $request->input('latitude'),
            $request->input('longitude')
        );

        $savedLocation = SavedQiblaLocation::create([
            'user_id' => Auth::id(),
            'location_name' => $request->input('location_name'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'qibla_direction' => $qiblaDirection,
            'distance_to_kaaba' => $distance,
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'country' => $request->input('country'),
            'usage_count' => 1,
            'last_accessed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location saved successfully',
            'location' => $savedLocation,
        ]);
    }

    /**
     * Get user's saved locations
     */
    public function getSavedLocations(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to view saved locations',
            ], 401);
        }

        $locations = SavedQiblaLocation::where('user_id', Auth::id())
            ->orderBy('usage_count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'locations' => $locations,
        ]);
    }

    /**
     * Load a saved location
     */
    public function loadLocation($id)
    {
        $location = SavedQiblaLocation::findOrFail($id);

        // Check authorization
        if (Auth::check() && $location->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Increment usage count
        $location->incrementUsage();

        return response()->json([
            'success' => true,
            'location' => $location,
            'qibla_direction' => $location->qibla_direction,
            'distance_km' => $location->distance_to_kaaba,
        ]);
    }

    /**
     * Delete a saved location
     */
    public function deleteSavedLocation($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $location = SavedQiblaLocation::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Location not found',
            ], 404);
        }

        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Location deleted successfully',
        ]);
    }

    /**
     * Toggle favorite status of a saved location
     */
    public function toggleFavorite($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $location = SavedQiblaLocation::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Location not found',
            ], 404);
        }

        $location->toggleFavorite();

        return response()->json([
            'success' => true,
            'is_favorite' => $location->is_favorite,
            'message' => $location->is_favorite ? 'Added to favorites' : 'Removed from favorites',
        ]);
    }

    /**
     * Log compass usage for analytics
     */
    private function logCompassUsage(Request $request, $latitude, $longitude, $qiblaDirection)
    {
        // Detect device type
        $userAgent = $request->header('User-Agent');
        $deviceType = 'desktop';
        
        if (preg_match('/mobile|android|iphone|ipad|tablet/i', $userAgent)) {
            $deviceType = preg_match('/tablet|ipad/i', $userAgent) ? 'tablet' : 'mobile';
        }

        QiblaCompassLog::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'qibla_direction' => $qiblaDirection,
            'device_type' => $deviceType,
            'browser' => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
            'accessed_at' => now(),
        ]);
    }
}
