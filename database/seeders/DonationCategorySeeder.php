<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonationCategory;

class DonationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Zakat',
                'description' => 'Obligatory charity for eligible Muslims, purifying wealth and supporting the needy.',
                'is_active' => true,
            ],
            [
                'name' => 'Sadaqah',
                'description' => 'Voluntary charity given out of compassion, seeking Allah\'s pleasure.',
                'is_active' => true,
            ],
            [
                'name' => 'Mosque Fund',
                'description' => 'Contributions for building, maintaining, and operating mosques.',
                'is_active' => true,
            ],
            [
                'name' => 'Orphan Support',
                'description' => 'Supporting orphaned children with care, education, and living expenses.',
                'is_active' => true,
            ],
            [
                'name' => 'Education',
                'description' => 'Funding Islamic education, scholarships, and educational materials.',
                'is_active' => true,
            ],
            [
                'name' => 'Medical Aid',
                'description' => 'Providing medical assistance and healthcare support to those in need.',
                'is_active' => true,
            ],
            [
                'name' => 'General Donation',
                'description' => 'General contributions to support various Islamic charitable causes.',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            DonationCategory::create($category);
        }

        $this->command->info('✅ Donation categories seeded successfully!');
    }
}
