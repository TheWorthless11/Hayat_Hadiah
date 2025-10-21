<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\PrayerTime;
use Carbon\Carbon;

class PrayerTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all locations
        $locations = Location::all();

        if ($locations->isEmpty()) {
            $this->command->info('No locations found. Please run LocationSeeder first.');
            return;
        }

        // Prayer times for major cities (sample data)
        $prayerTimesData = [
            'Dhaka' => [
                'fajr' => '04:50',
                'sunrise' => '05:59',
                'dhuhr' => '11:52',
                'asr' => '15:17',
                'maghrib' => '17:45',
                'isha' => '19:00',
                'imsak' => '04:40',
                'midnight' => '23:52',
                'qiyam' => '03:20',
            ],
            'Mecca' => [
                'fajr' => '05:15',
                'sunrise' => '06:30',
                'dhuhr' => '12:15',
                'asr' => '15:30',
                'maghrib' => '18:00',
                'isha' => '19:30',
                'imsak' => '05:05',
                'midnight' => '00:15',
                'qiyam' => '03:45',
            ],
            'Medina' => [
                'fajr' => '05:10',
                'sunrise' => '06:25',
                'dhuhr' => '12:10',
                'asr' => '15:25',
                'maghrib' => '17:55',
                'isha' => '19:25',
                'imsak' => '05:00',
                'midnight' => '00:10',
                'qiyam' => '03:40',
            ],
            'Cairo' => [
                'fajr' => '04:40',
                'sunrise' => '05:55',
                'dhuhr' => '11:45',
                'asr' => '15:05',
                'maghrib' => '17:35',
                'isha' => '18:55',
                'imsak' => '04:30',
                'midnight' => '23:45',
                'qiyam' => '03:15',
            ],
            'Istanbul' => [
                'fajr' => '05:30',
                'sunrise' => '06:50',
                'dhuhr' => '12:30',
                'asr' => '15:25',
                'maghrib' => '18:10',
                'isha' => '19:35',
                'imsak' => '05:20',
                'midnight' => '00:30',
                'qiyam' => '04:00',
            ],
            'Jakarta' => [
                'fajr' => '04:25',
                'sunrise' => '05:35',
                'dhuhr' => '11:50',
                'asr' => '15:10',
                'maghrib' => '18:05',
                'isha' => '19:15',
                'imsak' => '04:15',
                'midnight' => '23:50',
                'qiyam' => '03:25',
            ],
            'Kuala Lumpur' => [
                'fajr' => '05:45',
                'sunrise' => '06:55',
                'dhuhr' => '13:10',
                'asr' => '16:25',
                'maghrib' => '19:20',
                'isha' => '20:30',
                'imsak' => '05:35',
                'midnight' => '01:10',
                'qiyam' => '04:40',
            ],
        ];

        // Create prayer times for next 30 days for each location
        foreach ($locations as $location) {
            $cityName = $location->city;
            
            // Use default times if city not in our data
            $times = $prayerTimesData[$cityName] ?? $prayerTimesData['Dhaka'];

            // Generate prayer times for next 30 days
            for ($i = 0; $i < 30; $i++) {
                $date = Carbon::today()->addDays($i);

                PrayerTime::updateOrCreate(
                    [
                        'location_id' => $location->id,
                        'prayer_date' => $date,
                    ],
                    [
                        'imsak' => $times['imsak'],
                        'fajr' => $times['fajr'],
                        'sunrise' => $times['sunrise'],
                        'dhuhr' => $times['dhuhr'],
                        'asr' => $times['asr'],
                        'maghrib' => $times['maghrib'],
                        'isha' => $times['isha'],
                        'midnight' => $times['midnight'],
                        'qiyam' => $times['qiyam'],
                        'calculation_source' => 'Islamic Society of North America (ISNA)',
                    ]
                );
            }

            $this->command->info("Created prayer times for {$cityName} for next 30 days.");
        }

        $this->command->info('Prayer times seeded successfully!');
    }
}
