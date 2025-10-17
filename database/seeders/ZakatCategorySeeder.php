<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ZakatCategory;

class ZakatCategorySeeder extends Seeder
{
    /**
     * Seed the zakat_categories table.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Cash & Savings',
                'nisab_value' => null, // calculated dynamically based on gold/silver
                'currency' => 'TK',
                'description' => 'Cash in hand, bank accounts, savings, and liquid assets',
            ],
            [
                'name' => 'Gold',
                'nisab_value' => 85, // 85 grams
                'currency' => 'grams',
                'description' => 'Gold jewelry, bullion, coins (nisab: 85 grams)',
            ],
            [
                'name' => 'Silver',
                'nisab_value' => 595, // 595 grams
                'currency' => 'grams',
                'description' => 'Silver jewelry, bullion, coins (nisab: 595 grams)',
            ],
            [
                'name' => 'Business Assets',
                'nisab_value' => null,
                'currency' => 'TK',
                'description' => 'Stock, inventory, business capital, trade goods',
            ],
            [
                'name' => 'Investments',
                'nisab_value' => null,
                'currency' => 'TK',
                'description' => 'Stocks, bonds, mutual funds, real estate for investment',
            ],
            [
                'name' => 'Receivables',
                'nisab_value' => null,
                'currency' => 'TK',
                'description' => 'Money owed to you (loans given, receivables)',
            ],
        ];

        foreach ($categories as $category) {
            ZakatCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $this->command->info('✅ Zakat categories seeded successfully!');
    }
}
