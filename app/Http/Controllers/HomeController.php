<?php

namespace App\Http\Controllers;

use App\Services\PrayerService;
use App\Models\QuranVerse;
use App\Models\Hadith;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(PrayerService $prayerService)
    {
        // Get current date information
        $now = Carbon::now();
        $gregorianDate = $now->format('l, F j, Y');
        
        // Simple Hijri approximation (you can use a library for accurate conversion)
        $hijriYear = $now->year - 579;
        $hijriDate = "Rabi' al-Awwal {$now->day}, {$hijriYear} AH";

        // Get next prayer time (using Dhaka coordinates as default)
        $latitude = 23.8103;
        $longitude = 90.4125;
        $timezone = 'Asia/Dhaka';
        
        $prayerTimes = $prayerService->getPrayerTimesForCoordinates(
            $latitude,
            $longitude,
            $now->format('Y-m-d'),
            $timezone
        );

        // Find next prayer
        $nextPrayer = $this->getNextPrayer($prayerTimes);

        // Get Verse of the Day (random verse or you can use a specific logic)
        $verseOfDay = QuranVerse::inRandomOrder()->first();

        // Get Hadith of the Day (random hadith or specific logic)
        $hadithOfDay = Hadith::inRandomOrder()->first();

        return view('home', compact(
            'gregorianDate',
            'hijriDate',
            'nextPrayer',
            'verseOfDay',
            'hadithOfDay'
        ));
    }

    private function getNextPrayer($prayerTimes)
    {
        $now = Carbon::now();
        $prayers = ['fajr', 'dhuhr', 'asr', 'maghrib', 'isha'];
        
        foreach ($prayers as $prayer) {
            if (!isset($prayerTimes[$prayer])) continue;
            
            $prayerTime = Carbon::parse($prayerTimes[$prayer]);
            if ($prayerTime->isFuture()) {
                $diff = $now->diffInMinutes($prayerTime);
                $hours = floor($diff / 60);
                $minutes = $diff % 60;
                
                return [
                    'name' => ucfirst($prayer),
                    'time' => $prayerTime->format('g:i A'),
                    'countdown' => $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m",
                    'minutes_remaining' => $diff,
                ];
            }
        }

        // If no prayer left today, return Fajr tomorrow
        if (isset($prayerTimes['fajr'])) {
            $fajrTomorrow = Carbon::parse($prayerTimes['fajr'])->addDay();
            $diff = $now->diffInMinutes($fajrTomorrow);
            $hours = floor($diff / 60);
            $minutes = $diff % 60;
            
            return [
                'name' => 'Fajr',
                'time' => $fajrTomorrow->format('g:i A'),
                'countdown' => $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m",
                'minutes_remaining' => $diff,
            ];
        }
        
        // Fallback
        return [
            'name' => 'Fajr',
            'time' => 'N/A',
            'countdown' => 'N/A',
            'minutes_remaining' => 0,
        ];
    }
}
