<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MosqueController extends Controller
{
    /**
     * Display the nearby mosques finder page
     */
    public function index()
    {
        return view('mosque.index');
    }

    /**
     * Fetch nearby mosques using OpenStreetMap Overpass API (Free, No API Key)
     */
    public function fetchNearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'sometimes|integer|min:500|max:50000',
        ]);

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 5000); // Default 5km

        try {
            // OpenStreetMap Overpass API query for mosques
            // Find all nodes and ways tagged as mosque within radius
            $overpassQuery = <<<QUERY
[out:json][timeout:25];
(
  node["amenity"="place_of_worship"]["religion"="muslim"](around:{$radius},{$latitude},{$longitude});
  way["amenity"="place_of_worship"]["religion"="muslim"](around:{$radius},{$latitude},{$longitude});
  node["building"="mosque"](around:{$radius},{$latitude},{$longitude});
  way["building"="mosque"](around:{$radius},{$latitude},{$longitude});
);
out body;
>;
out skel qt;
QUERY;

            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'HayatHadiah/1.0 (Islamic App)',
                ])
                ->get('https://overpass-api.de/api/interpreter', [
                    'data' => $overpassQuery
                ]);

            if ($response->failed()) {
                Log::error('Overpass API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json(['error' => 'Failed to fetch nearby mosques from OpenStreetMap'], 500);
            }

            $data = $response->json();
            
            if (!isset($data['elements'])) {
                return response()->json(['error' => 'Invalid response from OpenStreetMap'], 500);
            }

            // Process OSM elements
            $mosques = collect($data['elements'])->filter(function ($element) {
                // Filter to only include mosques with names
                return isset($element['tags']['name']) || isset($element['tags']['name:en']);
            })->map(function ($element) use ($latitude, $longitude) {
                // Get coordinates (handle both nodes and ways)
                $lat = null;
                $lon = null;
                
                if ($element['type'] === 'node') {
                    $lat = $element['lat'];
                    $lon = $element['lon'];
                } elseif ($element['type'] === 'way' && isset($element['center'])) {
                    $lat = $element['center']['lat'];
                    $lon = $element['center']['lon'];
                }
                
                if (!$lat || !$lon) {
                    return null;
                }

                $tags = $element['tags'] ?? [];
                
                // Get mosque name (try multiple language tags)
                $name = $tags['name'] 
                    ?? $tags['name:en'] 
                    ?? $tags['name:ar'] 
                    ?? $tags['alt_name'] 
                    ?? 'Unnamed Mosque';

                // Get address components
                $address = $this->buildAddress($tags);
                
                // Calculate distance
                $distance = $this->calculateDistance($latitude, $longitude, $lat, $lon);

                return [
                    'place_id' => 'osm_' . $element['type'] . '_' . $element['id'],
                    'name' => $name,
                    'address' => $address,
                    'latitude' => $lat,
                    'longitude' => $lon,
                    'distance' => $distance,
                    'denomination' => $tags['denomination'] ?? null,
                    'capacity' => $tags['capacity'] ?? null,
                    'wheelchair' => $tags['wheelchair'] ?? null,
                    'website' => $tags['website'] ?? $tags['contact:website'] ?? null,
                    'phone' => $tags['phone'] ?? $tags['contact:phone'] ?? null,
                ];
            })->filter()->sortBy('distance')->values();

            // If no results, try a broader search for Islamic centers
            if ($mosques->isEmpty()) {
                return $this->fetchIslamicCenters($latitude, $longitude, $radius);
            }

            return response()->json([
                'status' => 'success',
                'count' => $mosques->count(),
                'mosques' => $mosques,
                'source' => 'OpenStreetMap',
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching nearby mosques from OSM', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'An error occurred while fetching nearby mosques. Please try again.'
            ], 500);
        }
    }

    /**
     * Fallback: Fetch Islamic centers if no mosques found
     */
    private function fetchIslamicCenters($latitude, $longitude, $radius)
    {
        try {
            $overpassQuery = <<<QUERY
[out:json][timeout:25];
(
  node["amenity"="community_centre"]["religion"="muslim"](around:{$radius},{$latitude},{$longitude});
  way["amenity"="community_centre"]["religion"="muslim"](around:{$radius},{$latitude},{$longitude});
  node["amenity"="place_of_worship"](around:{$radius},{$latitude},{$longitude});
  way["amenity"="place_of_worship"](around:{$radius},{$latitude},{$longitude});
);
out body;
>;
out skel qt;
QUERY;

            $response = Http::timeout(30)
                ->withHeaders(['User-Agent' => 'HayatHadiah/1.0 (Islamic App)'])
                ->get('https://overpass-api.de/api/interpreter', ['data' => $overpassQuery]);

            if ($response->successful()) {
                $data = $response->json();
                $centers = collect($data['elements'] ?? [])->map(function ($element) use ($latitude, $longitude) {
                    $lat = $element['lat'] ?? $element['center']['lat'] ?? null;
                    $lon = $element['lon'] ?? $element['center']['lon'] ?? null;
                    
                    if (!$lat || !$lon) return null;
                    
                    $tags = $element['tags'] ?? [];
                    return [
                        'place_id' => 'osm_' . $element['type'] . '_' . $element['id'],
                        'name' => $tags['name'] ?? 'Islamic Center',
                        'address' => $this->buildAddress($tags),
                        'latitude' => $lat,
                        'longitude' => $lon,
                        'distance' => $this->calculateDistance($latitude, $longitude, $lat, $lon),
                    ];
                })->filter()->sortBy('distance')->values();

                return response()->json([
                    'status' => 'success',
                    'count' => $centers->count(),
                    'mosques' => $centers,
                    'source' => 'OpenStreetMap (Islamic Centers)',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Fallback search failed', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'status' => 'success',
            'count' => 0,
            'mosques' => [],
            'source' => 'OpenStreetMap',
        ]);
    }

    /**
     * Build address from OSM tags
     */
    private function buildAddress(array $tags): string
    {
        $parts = [];
        
        if (isset($tags['addr:street'])) {
            $parts[] = $tags['addr:street'];
        }
        if (isset($tags['addr:housenumber'])) {
            $parts[] = $tags['addr:housenumber'];
        }
        if (isset($tags['addr:city'])) {
            $parts[] = $tags['addr:city'];
        }
        if (isset($tags['addr:state'])) {
            $parts[] = $tags['addr:state'];
        }
        if (isset($tags['addr:postcode'])) {
            $parts[] = $tags['addr:postcode'];
        }
        
        return !empty($parts) ? implode(', ', $parts) : 'Address not available';
    }

    /**
     * Calculate distance between two coordinates (Haversine formula)
     * Returns distance in kilometers
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371; // kilometers

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDiff / 2) * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }
}
