<?php

namespace App\Services;

use Carbon\Carbon;

/**
 * Minimal prayer time calculator based on common solar formulas.
 * This is a compact implementation for demonstration and basic accuracy.
 * For production consider using a well-tested library.
 */
class PrayerCalculator
{
    // Calculation methods constants (a few common ones)
    public const METHOD_MWL = 'MWL'; // Muslim World League
    public const METHOD_ISNA = 'ISNA';
    public const METHOD_EGYPT = 'Egypt';
    public const METHOD_Makkah = 'Makkah';

    protected string $method;

    public function __construct(string $method = self::METHOD_MWL)
    {
        $this->method = $method;
    }

    /**
     * Compute prayer times for a given date and coordinates.
     * @param string $date YYYY-MM-DD
     * @param float $lat
     * @param float $lng
     * @param string $timezone e.g. 'Asia/Karachi'
     * @return array times formatted H:i
     */
    public function compute(string $date, float $lat, float $lng, string $timezone): array
    {
        $d = Carbon::createFromFormat('Y-m-d', $date, $timezone)->startOfDay();
        $jd = $this->julianDate($d->year, $d->month, $d->day);

        // Get timezone offset in hours
        $tzOffset = $d->offsetHours;

        // solar declination & equation
        $D = $jd - 2451545.0;
        $g = $this->fixAngle(357.529 + 0.98560028 * $D);
        $q = $this->fixAngle(280.459 + 0.98564736 * $D);
        $L = $this->fixAngle($q + 1.915 * sin($this->deg2rad($g)) + 0.020 * sin($this->deg2rad(2 * $g)));
        $e = 23.439 - 0.00000036 * $D;
        $RA = $this->rad2deg(atan2(cos($this->deg2rad($e)) * sin($this->deg2rad($L)), cos($this->deg2rad($L)))) / 15.0;
        $decl = $this->rad2deg(asin(sin($this->deg2rad($e)) * sin($this->deg2rad($L))));

        $eqt = $q / 15.0 - $this->fixHour($RA);

        // Noon time (when sun crosses meridian)
        // Add timezone offset to get local time
        $noon = 12 - $lng / 15.0 - $eqt + $tzOffset;

        // helper closure to compute time from sun altitude angle
        $computeTime = function ($angle) use ($decl, $lat, $noon) {
            $term = (sin($this->deg2rad($angle)) - sin($this->deg2rad($decl)) * sin($this->deg2rad($lat))) /
                (cos($this->deg2rad($decl)) * cos($this->deg2rad($lat)));
            if ($term > 1 || $term < -1) {
                return null;
            }
            $hour = $this->rad2deg(acos($term)) / 15.0;
            return [$noon - $hour, $noon + $hour];
        };

        // Get angles for calculation method
        $angles = $this->getMethodAngles();
        
        // Calculate times
        $fajrTime = $computeTime($angles['fajr']);
        $sunriseTime = $computeTime(-0.833);
        $asrTime = $this->computeAsr($lat, $decl, $noon);
        $maghribTime = $computeTime($angles['maghrib']);
        $ishaTime = $computeTime($angles['isha']);

        // Format results
        $result = [
            'fajr' => $fajrTime && $fajrTime[0] ? $this->floatToTime($fajrTime[0]) : null,
            'sunrise' => $sunriseTime && $sunriseTime[0] ? $this->floatToTime($sunriseTime[0]) : null,
            'dhuhr' => $this->floatToTime($noon),
            'asr' => $asrTime && $asrTime[1] ? $this->floatToTime($asrTime[1]) : null,
            'maghrib' => $maghribTime && $maghribTime[1] ? $this->floatToTime($maghribTime[1]) : null,
            'isha' => $ishaTime && $ishaTime[1] ? $this->floatToTime($ishaTime[1]) : null,
        ];

        return $result;
    }

    protected function getMethodAngles(): array
    {
        // Return Fajr and Isha angles based on calculation method
        switch ($this->method) {
            case self::METHOD_ISNA:
                return ['fajr' => -15, 'maghrib' => -0.833, 'isha' => -15];
            case self::METHOD_EGYPT:
                return ['fajr' => -19.5, 'maghrib' => -0.833, 'isha' => -17.5];
            case self::METHOD_Makkah:
                return ['fajr' => -18.5, 'maghrib' => -0.833, 'isha' => -90]; // 90 min after maghrib
            case self::METHOD_MWL:
            default:
                return ['fajr' => -18, 'maghrib' => -0.833, 'isha' => -17];
        }
    }

    protected function floatToTime($hour): string
    {
        $hour = $this->fixHour($hour);
        $h = floor($hour);
        $m = floor(($hour - $h) * 60);
        return sprintf('%02d:%02d', $h, $m);
    }

    protected function computeAsr($lat, $decl, $noon)
    {
        // Asr calculation: shadow length = 1 + tan(latitude - declination)
        $factor = 1; // Shafi: 1, Hanafi: 2
        
        // Calculate the sun altitude angle for Asr
        $angle = $this->rad2deg(atan(1 / ($factor + tan($this->deg2rad(abs($lat - $decl))))));
        
        $term = (sin($this->deg2rad($angle)) - sin($this->deg2rad($decl)) * sin($this->deg2rad($lat))) /
            (cos($this->deg2rad($decl)) * cos($this->deg2rad($lat)));
            
        if ($term > 1 || $term < -1) {
            return [null, null];
        }
        
        $hour = $this->rad2deg(acos($term)) / 15.0;
        return [$noon - $hour, $noon + $hour];
    }

    protected function julianDate($y, $m, $d)
    {
        if ($m <= 2) {
            $y -= 1;
            $m += 12;
        }
        $A = floor($y / 100);
        $B = 2 - $A + floor($A / 4);
        return floor(365.25 * ($y + 4716)) + floor(30.6001 * ($m + 1)) + $d + $B - 1524.5;
    }

    protected function deg2rad($d) { return $d * M_PI / 180.0; }
    protected function rad2deg($r) { return $r * 180.0 / M_PI; }
    protected function fixAngle($a) { return $a - 360.0 * floor($a / 360.0); }
    protected function fixHour($h) { return $h - 24.0 * floor($h / 24.0); }
}
