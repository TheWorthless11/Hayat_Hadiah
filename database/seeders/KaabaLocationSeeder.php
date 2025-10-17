<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KaabaLocation;

class KaabaLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KaabaLocation::updateOrCreate(
            ['id' => 1],
            [
                'latitude' => 21.4225,
                'longitude' => 39.8262,
                'location_name' => 'Holy Kaaba, Masjid al-Haram',
                'city' => 'Mecca',
                'country' => 'Saudi Arabia',
                'description' => 'The Holy Kaaba (الكعبة المشرفة) is the most sacred site in Islam, located in the center of Masjid al-Haram in Mecca, Saudi Arabia. Muslims around the world face the Kaaba during their daily prayers (Salah).',
            ]
        );
    }
}
