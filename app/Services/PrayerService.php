<?php

namespace App\Services;

use App\Models\Location;
use App\Models\PrayerTime;
use App\Services\PrayerCalculator;
use Carbon\Carbon;

class PrayerService
{
    public function getPrayerTimesForDate(Location $location, string $date)
    {
        $prayer = PrayerTime::where('location_id', $location->id)
            ->where('prayer_date', $date)
            ->first();

        if ($prayer) {
            return [
                'imsak' => optional($prayer->imsak)->format('H:i'),
                'fajr' => optional($prayer->fajr)->format('H:i'),
                'sunrise' => optional($prayer->sunrise)->format('H:i'),
                'dhuhr' => optional($prayer->dhuhr)->format('H:i'),
                'asr' => optional($prayer->asr)->format('H:i'),
                'maghrib' => optional($prayer->maghrib)->format('H:i'),
                'isha' => optional($prayer->isha)->format('H:i'),
                'midnight' => optional($prayer->midnight)->format('H:i'),
                'qiyam' => optional($prayer->qiyam)->format('H:i'),
                'calculation_source' => $prayer->calculation_source,
                'adjustments' => $prayer->adjustments ?: [],
            ];
        }

        // Fall back to dynamic calculation
        $calculator = new PrayerCalculator($location->calculation_method ?? PrayerCalculator::METHOD_MWL);

        // ensure timezone and coords exist
        if (! $location->latitude || ! $location->longitude || ! $location->timezone) {
            return null;
        }

        $calculated = $calculator->compute($date, (float)$location->latitude, (float)$location->longitude, $location->timezone);

        return [
            'imsak' => $calculated['imsak'] ?? null,
            'fajr' => $calculated['fajr'] ?? null,
            'sunrise' => $calculated['sunrise'] ?? null,
            'dhuhr' => $calculated['dhuhr'] ?? null,
            'asr' => $calculated['asr'] ?? null,
            'maghrib' => $calculated['maghrib'] ?? null,
            'isha' => $calculated['isha'] ?? null,
            'calculation_source' => 'calculated:' . ($location->calculation_method ?? PrayerCalculator::METHOD_MWL),
            'adjustments' => [],
        ];
    }

    /**
     * Calculate prayer times directly from coordinates using islamic-network/prayer-times library.
     * @param float $lat
     * @param float $lng
     * @param string $date YYYY-MM-DD
     * @param string $timezone
     * @param string|null $method
     * @param string|null $school
     * @return array|null
     */
    public function getPrayerTimesForCoordinates(float $lat, float $lng, string $date, string $timezone, ?string $method = null, ?string $school = null)
    {
        try {
            // Use islamic-network/prayer-times library
            if (class_exists('IslamicNetwork\PrayerTimes\PrayerTimes')) {
                // Determine the school (Hanafi or Standard)
                $asrSchool = ($school === 'HANAFI') 
                    ? \IslamicNetwork\PrayerTimes\PrayerTimes::SCHOOL_HANAFI 
                    : \IslamicNetwork\PrayerTimes\PrayerTimes::SCHOOL_STANDARD;
                
                $pt = new \IslamicNetwork\PrayerTimes\PrayerTimes($method ?? 'MWL', $asrSchool);
                
                // Create DateTime object for the date
                $dateTime = new \DateTime($date, new \DateTimeZone($timezone));
                
                // Get prayer times using the library
                $times = $pt->getTimes(
                    $dateTime,
                    $lat,
                    $lng,
                    null, // elevation
                    \IslamicNetwork\PrayerTimes\PrayerTimes::LATITUDE_ADJUSTMENT_METHOD_ANGLE,
                    null, // midnightMode
                    \IslamicNetwork\PrayerTimes\PrayerTimes::TIME_FORMAT_24H
                );
                
                // Format the response using the correct constant keys
                $result = [
                    'fajr' => $times[\IslamicNetwork\PrayerTimes\PrayerTimes::FAJR] ?? null,
                    'sunrise' => $times[\IslamicNetwork\PrayerTimes\PrayerTimes::SUNRISE] ?? null,
                    'dhuhr' => $times[\IslamicNetwork\PrayerTimes\PrayerTimes::ZHUHR] ?? null,
                    'asr' => $times[\IslamicNetwork\PrayerTimes\PrayerTimes::ASR] ?? null,
                    'maghrib' => $times[\IslamicNetwork\PrayerTimes\PrayerTimes::MAGHRIB] ?? null,
                    'isha' => $times[\IslamicNetwork\PrayerTimes\PrayerTimes::ISHA] ?? null,
                    'midnight' => $times[\IslamicNetwork\PrayerTimes\PrayerTimes::MIDNIGHT] ?? null,
                    'qiyam' => $times[\IslamicNetwork\PrayerTimes\PrayerTimes::LAST_THIRD] ?? null,
                    'calculation_source' => 'islamic-network/prayer-times:' . ($method ?? 'MWL') . '/' . ($school ?? 'STANDARD'),
                    'adjustments' => [],
                ];
                
                return $result;
            }
        } catch (\Throwable $e) {
            // Log error for debugging
            \Log::error('Prayer time calculation error: ' . $e->getMessage());
        }

        // Fallback: use internal PrayerCalculator
        $calculator = new PrayerCalculator($method ?? PrayerCalculator::METHOD_MWL);
        $calculated = $calculator->compute($date, $lat, $lng, $timezone);

        if (is_array($calculated)) {
            $calculated['calculation_source'] = $calculated['calculation_source'] ?? 'calculated:' . ($method ?? PrayerCalculator::METHOD_MWL);
            $calculated['adjustments'] = $calculated['adjustments'] ?? [];
        }

        return $calculated;
    }
}
