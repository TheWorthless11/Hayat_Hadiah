<?php

namespace App\Http\Controllers;

use App\Models\FastingSchedule;
use App\Models\Location;
use App\Services\PrayerService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FastingController extends Controller
{
    public function __construct(private PrayerService $prayerService)
    {
    }

    /**
     * Show fasting calendar generator UI
     */
    public function index(Request $request)
    {
        $locations = Location::select('id', 'name', 'city', 'country')->orderBy('id')->get();

        return view('fasting.index', [
            'locations' => $locations,
            'defaultMonth' => now()->format('Y-m'),
        ]);
    }

    /**
     * Generate fasting schedule for a month and location.
     * POST params: month (Y-m), location_id, persist (bool, default true), is_ramadan (bool)
     */
    public function generate(Request $request)
    {
        $data = $request->validate([
            'month' => ['required', 'date_format:Y-m'],
            'location_id' => ['required', 'exists:locations,id'],
            'persist' => ['sometimes', 'boolean'],
            'is_ramadan' => ['sometimes', 'boolean'],
        ]);

        $persist = (bool)($data['persist'] ?? true);
        $isRamadan = (bool)($data['is_ramadan'] ?? false);

        $location = Location::findOrFail($data['location_id']);

        $start = Carbon::createFromFormat('Y-m-d', $data['month'] . '-01')->startOfMonth();
        $end = (clone $start)->endOfMonth();

        $results = [];
        $dayCounter = 0;

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dateStr = $date->format('Y-m-d');

            $times = $this->prayerService->getPrayerTimesForDate($location, $dateStr);
            if (!$times) {
                // Skip if we cannot compute times for this date
                continue;
            }

            // Sehri = 10 minutes before Fajr if Imsak not provided
            $sehriObj = null;
            if (!empty($times['imsak'])) {
                $sehriObj = Carbon::createFromFormat('Y-m-d H:i', "$dateStr {$times['imsak']}");
            } elseif (!empty($times['fajr'])) {
                $sehriObj = Carbon::createFromFormat('Y-m-d H:i', "$dateStr {$times['fajr']}")->subMinutes(10);
            }

            $iftarObj = null;
            if (!empty($times['maghrib'])) {
                $iftarObj = Carbon::createFromFormat('Y-m-d H:i', "$dateStr {$times['maghrib']}");
            }

            $payload = [
                'location_id'   => $location->id,
                'gregorian_date'=> $dateStr,
                'hijri_date'    => null, // TODO: add Hijri conversion in future
                'sehri_time'    => $sehriObj?->format('H:i:s'),
                'iftar_time'    => $iftarObj?->format('H:i:s'),
                'is_ramadan'    => $isRamadan,
                'ramadan_day'   => $isRamadan ? (++$dayCounter) : null,
                'meta'          => [
                    'calculation_source' => $times['calculation_source'] ?? 'unknown',
                    'note' => $isRamadan ? 'Ramadan day ' . $dayCounter : null,
                ],
            ];

            if ($persist) {
                FastingSchedule::updateOrCreate(
                    [
                        'location_id' => $location->id,
                        'gregorian_date' => $dateStr,
                    ],
                    $payload
                );
            }

            $results[] = [
                'date' => $dateStr,
                'sehri' => $payload['sehri_time'],
                'iftar' => $payload['iftar_time'],
                'sehri_display' => $sehriObj?->format('g:i A'),
                'iftar_display' => $iftarObj?->format('g:i A'),
                'is_ramadan' => $payload['is_ramadan'],
                'ramadan_day' => $payload['ramadan_day'],
                'source' => $times['calculation_source'] ?? null,
                'saved' => $persist,
            ];
        }

        return response()->json([
            'status' => 'ok',
            'count' => count($results),
            'location' => $location->only(['id', 'name', 'city', 'country']),
            'month' => $data['month'],
            'persisted' => $persist,
            'results' => $results,
        ]);
    }
}
