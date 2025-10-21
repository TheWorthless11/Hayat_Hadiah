<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrayerTime;
use App\Models\QuranVerse;
use App\Models\Hadith;
use App\Models\Dua;
use App\Models\DonationCategory;
use App\Models\Mosque;
use App\Models\FastingSchedule;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ChatbotApiController extends Controller
{
    /**
     * Get prayer times for today
     */
    public function getPrayerTimes(Request $request)
    {
        $locationName = $request->input('location', 'Dhaka, Bangladesh');
        
        // Get today's prayer times (try to find by location name or use first available)
        $prayerTimes = PrayerTime::with('location')
            ->whereDate('prayer_date', today())
            ->whereHas('location', function($query) use ($locationName) {
                $query->where('city', 'like', "%{$locationName}%")
                      ->orWhere('country', 'like', "%{$locationName}%");
            })
            ->first();
        
        if (!$prayerTimes) {
            // Get default prayer times (first one for today)
            $prayerTimes = PrayerTime::with('location')
                ->whereDate('prayer_date', today())
                ->first();
        }
        
        if (!$prayerTimes) {
            return response()->json([
                'error' => 'Prayer times not available for today'
            ], 404);
        }
        
        return response()->json([
            'date' => $prayerTimes->prayer_date->format('Y-m-d'),
            'location' => $prayerTimes->location->city . ', ' . $prayerTimes->location->country,
            'prayer_times' => [
                ['name' => 'Fajr', 'time' => $prayerTimes->fajr],
                ['name' => 'Dhuhr', 'time' => $prayerTimes->dhuhr],
                ['name' => 'Asr', 'time' => $prayerTimes->asr],
                ['name' => 'Maghrib', 'time' => $prayerTimes->maghrib],
                ['name' => 'Isha', 'time' => $prayerTimes->isha],
            ]
        ]);
    }
    
    /**
     * Get specific prayer time
     */
    public function getSpecificPrayer(Request $request, $prayerName)
    {
        $location = $request->input('location', 'Dhaka, Bangladesh');
        $prayerName = strtolower($prayerName);
        
        $prayerTimes = PrayerTime::whereDate('date', today())
            ->where('location', 'like', "%{$location}%")
            ->first();
        
        if (!$prayerTimes) {
            $prayerTimes = PrayerTime::whereDate('date', today())->first();
        }
        
        if (!$prayerTimes) {
            return response()->json(['error' => 'Prayer times not available'], 404);
        }
        
        // Map prayer names
        $prayerMap = [
            'fajr' => $prayerTimes->fajr,
            'dhuhr' => $prayerTimes->dhuhr,
            'zuhr' => $prayerTimes->dhuhr,
            'asr' => $prayerTimes->asr,
            'maghrib' => $prayerTimes->maghrib,
            'isha' => $prayerTimes->isha,
        ];
        
        if (!isset($prayerMap[$prayerName])) {
            return response()->json(['error' => 'Invalid prayer name'], 400);
        }
        
        return response()->json([
            'prayer_name' => ucfirst($prayerName),
            'time' => $prayerMap[$prayerName],
            'date' => $prayerTimes->date->format('Y-m-d'),
        ]);
    }
    
    /**
     * Get Qibla direction
     */
    public function getQiblaDirection(Request $request)
    {
        $location = $request->input('location', 'Dhaka, Bangladesh');
        
        // For simplicity, return Kaaba coordinates
        // In production, calculate based on user location
        $kaaba_lat = 21.4225;
        $kaaba_lng = 39.8262;
        
        // Default user location (Dhaka)
        $user_lat = 23.8103;
        $user_lng = 90.4125;
        
        // Calculate bearing (simplified)
        $dLon = deg2rad($kaaba_lng - $user_lng);
        $y = sin($dLon) * cos(deg2rad($kaaba_lat));
        $x = cos(deg2rad($user_lat)) * sin(deg2rad($kaaba_lat)) -
              sin(deg2rad($user_lat)) * cos(deg2rad($kaaba_lat)) * cos($dLon);
        $bearing = atan2($y, $x);
        $bearing = rad2deg($bearing);
        $bearing = ($bearing + 360) % 360;
        
        // Calculate distance
        $earthRadius = 6371; // km
        $dLat = deg2rad($kaaba_lat - $user_lat);
        $dLon = deg2rad($kaaba_lng - $user_lng);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($user_lat)) * cos(deg2rad($kaaba_lat)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;
        
        return response()->json([
            'direction' => round($bearing, 2),
            'distance' => round($distance, 2),
            'location' => $location
        ]);
    }
    
    /**
     * Get Quran verse by topic
     */
    public function getQuranVerse(Request $request)
    {
        $topic = $request->input('topic', 'random');
        
        $query = QuranVerse::query();
        
        if ($topic !== 'random') {
            $query->where(function($q) use ($topic) {
                $q->where('text_english', 'like', "%{$topic}%")
                  ->orWhere('topic', 'like', "%{$topic}%");
            });
        }
        
        $verse = $query->inRandomOrder()->first();
        
        if (!$verse) {
            return response()->json(['error' => 'Verse not found'], 404);
        }
        
        return response()->json([
            'surah_number' => $verse->surah_number,
            'verse_number' => $verse->verse_number,
            'surah_name' => $verse->surah_name,
            'arabic' => $verse->text_arabic,
            'translation' => $verse->text_english,
            'topic' => $verse->topic
        ]);
    }
    
    /**
     * Get random Quran verse
     */
    public function getRandomQuranVerse()
    {
        $verse = QuranVerse::inRandomOrder()->first();
        
        if (!$verse) {
            return response()->json(['error' => 'Verse not found'], 404);
        }
        
        return response()->json([
            'surah_number' => $verse->surah_number,
            'verse_number' => $verse->verse_number,
            'surah_name' => $verse->surah_name,
            'arabic' => $verse->text_arabic,
            'translation' => $verse->text_english,
            'topic' => $verse->topic
        ]);
    }
    
    /**
     * Get Hadith by topic
     */
    public function getHadith(Request $request)
    {
        $topic = $request->input('topic', 'random');
        
        $query = Hadith::query();
        
        if ($topic !== 'random') {
            $query->where(function($q) use ($topic) {
                $q->where('text_english', 'like', "%{$topic}%")
                  ->orWhere('topic', 'like', "%{$topic}%");
            });
        }
        
        $hadith = $query->inRandomOrder()->first();
        
        if (!$hadith) {
            return response()->json(['error' => 'Hadith not found'], 404);
        }
        
        return response()->json([
            'collection' => $hadith->collection,
            'book' => $hadith->book,
            'hadith_number' => $hadith->hadith_number,
            'arabic' => $hadith->text_arabic,
            'translation' => $hadith->text_english,
            'reference' => "{$hadith->collection}, Book {$hadith->book}, Hadith {$hadith->hadith_number}",
            'topic' => $hadith->topic
        ]);
    }
    
    /**
     * Get random Hadith
     */
    public function getRandomHadith()
    {
        $hadith = Hadith::inRandomOrder()->first();
        
        if (!$hadith) {
            return response()->json(['error' => 'Hadith not found'], 404);
        }
        
        return response()->json([
            'collection' => $hadith->collection,
            'book' => $hadith->book,
            'hadith_number' => $hadith->hadith_number,
            'arabic' => $hadith->text_arabic,
            'translation' => $hadith->text_english,
            'reference' => "{$hadith->collection}, Book {$hadith->book}, Hadith {$hadith->hadith_number}",
            'topic' => $hadith->topic
        ]);
    }
    
    /**
     * Get all duas
     */
    public function getDuas()
    {
        $duas = Dua::select('id', 'title', 'category')
            ->orderBy('category')
            ->orderBy('title')
            ->get();
        
        return response()->json([
            'duas' => $duas
        ]);
    }
    
    /**
     * Get specific dua by category
     */
    public function getSpecificDua($category)
    {
        $dua = Dua::where('category', 'like', "%{$category}%")
            ->inRandomOrder()
            ->first();
        
        if (!$dua) {
            return response()->json(['error' => 'Dua not found'], 404);
        }
        
        return response()->json([
            'id' => $dua->id,
            'title' => $dua->title,
            'arabic' => $dua->arabic_text,
            'transliteration' => $dua->transliteration,
            'translation' => $dua->translation,
            'category' => $dua->category,
            'reference' => $dua->reference
        ]);
    }
    
    /**
     * Get Zakat information
     */
    public function getZakatInfo()
    {
        return response()->json([
            'description' => 'Zakat is one of the Five Pillars of Islam. It is a form of almsgiving and religious tax, obligatory for all Muslims who meet the necessary criteria of wealth.',
            'nisab' => '₹52,000 (approximate value of 87.48 grams of gold or 612.36 grams of silver)',
            'rate' => '2.5% of total wealth held for one lunar year',
            'eligible_wealth' => [
                'Cash and bank balances',
                'Gold and silver',
                'Business inventory',
                'Investment properties',
                'Stocks and shares'
            ],
            'exempt_items' => [
                'Personal residence',
                'Personal vehicle',
                'Household items',
                'Professional tools'
            ]
        ]);
    }
    
    /**
     * Get donation information
     */
    public function getDonationInfo()
    {
        $categories = DonationCategory::select('id', 'name', 'description', 'goal_amount')
            ->withCount('donations')
            ->get()
            ->map(function($category) {
                $totalRaised = $category->donations()
                    ->where('payment_status', 'successful')
                    ->sum('amount');
                
                $progress = 0;
                if ($category->goal_amount && $category->goal_amount > 0) {
                    $progress = round(($totalRaised / $category->goal_amount) * 100, 1);
                }
                
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'goal_amount' => $category->goal_amount,
                    'total_raised' => $totalRaised,
                    'progress' => $progress
                ];
            });
        
        return response()->json([
            'categories' => $categories
        ]);
    }
    
    /**
     * Find nearby mosques
     */
    public function findNearbyMosques(Request $request)
    {
        $location = $request->input('location', 'Dhaka, Bangladesh');
        
        // Get mosques (simplified - in production, use geospatial queries)
        $mosques = Mosque::select('id', 'name', 'address', 'latitude', 'longitude', 'contact_number')
            ->where('address', 'like', "%{$location}%")
            ->orWhere('city', 'like', "%{$location}%")
            ->limit(10)
            ->get()
            ->map(function($mosque) {
                return [
                    'id' => $mosque->id,
                    'name' => $mosque->name,
                    'address' => $mosque->address,
                    'distance' => rand(1, 10) . ' km', // Placeholder
                    'latitude' => $mosque->latitude,
                    'longitude' => $mosque->longitude,
                    'contact' => $mosque->contact_number
                ];
            });
        
        return response()->json([
            'mosques' => $mosques,
            'location' => $location
        ]);
    }
    
    /**
     * Get fasting times
     */
    public function getFastingTimes(Request $request)
    {
        $location = $request->input('location', 'Dhaka, Bangladesh');
        
        // Get today's fasting schedule
        $fasting = FastingSchedule::whereDate('date', today())
            ->where('location', 'like', "%{$location}%")
            ->first();
        
        if (!$fasting) {
            // Use prayer times as fallback
            $prayerTimes = PrayerTime::whereDate('date', today())->first();
            
            if (!$prayerTimes) {
                return response()->json(['error' => 'Fasting times not available'], 404);
            }
            
            $suhoorEnd = Carbon::parse($prayerTimes->fajr)->format('h:i A');
            $iftarTime = Carbon::parse($prayerTimes->maghrib)->format('h:i A');
            
            return response()->json([
                'date' => today()->format('F d, Y'),
                'suhoor_end' => $suhoorEnd,
                'iftar_time' => $iftarTime,
                'duration' => $this->calculateDuration($suhoorEnd, $iftarTime),
                'location' => $location
            ]);
        }
        
        return response()->json([
            'date' => $fasting->date->format('F d, Y'),
            'suhoor_end' => $fasting->suhoor_end_time,
            'iftar_time' => $fasting->iftar_time,
            'duration' => $this->calculateDuration($fasting->suhoor_end_time, $fasting->iftar_time),
            'location' => $fasting->location
        ]);
    }
    
    /**
     * Get Ramadan information
     */
    public function getRamadanInfo()
    {
        // Ramadan 2025 dates (approximate)
        $ramadanStart = Carbon::create(2025, 3, 1);
        $ramadanEnd = Carbon::create(2025, 3, 30);
        $currentYear = now()->year;
        
        $daysRemaining = now()->diffInDays($ramadanStart, false);
        
        $message = '';
        if ($daysRemaining > 0) {
            $message = "Ramadan is {$daysRemaining} days away. May Allah allow us to witness this blessed month.";
        } elseif ($daysRemaining < -30) {
            $message = "Ramadan has passed. May Allah accept our fasts and deeds.";
        } else {
            $message = "We are currently in the blessed month of Ramadan. May Allah accept our fasts.";
        }
        
        return response()->json([
            'year' => $currentYear,
            'start_date' => $ramadanStart->format('F d, Y'),
            'end_date' => $ramadanEnd->format('F d, Y'),
            'days_remaining' => max(0, $daysRemaining),
            'message' => $message
        ]);
    }
    
    /**
     * Calculate fasting duration
     */
    private function calculateDuration($startTime, $endTime)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        
        $diff = $start->diff($end);
        
        return "{$diff->h} hours {$diff->i} minutes";
    }
}
