<?php

namespace App\Services;

class ZakatService
{
    // Nisab thresholds
    const NISAB_GOLD_GRAMS = 85;   // 85g of gold
    const NISAB_SILVER_GRAMS = 595; // 595g of silver

    public function calculate(array $input): array
    {
        $cash = (float)($input['cash'] ?? 0);
        $goldGrams = (float)($input['gold_grams'] ?? 0);
        $silverGrams = (float)($input['silver_grams'] ?? 0);
        $businessAssets = (float)($input['business_assets'] ?? 0);
        $receivables = (float)($input['receivables'] ?? 0);
        $investments = (float)($input['investments'] ?? 0);
        $liabilities = (float)($input['liabilities'] ?? 0);

        $goldPrice = (float)$input['gold_price_per_gram'];
        $silverPrice = (float)$input['silver_price_per_gram'];
        $basis = $input['basis']; // 'gold' or 'silver'
        $rate = ((float)$input['zakat_rate']) / 100.0; // e.g., 2.5% => 0.025
        $currency = $input['currency'] ?? 'USD';
        $year = (int)($input['year'] ?? (int)date('Y'));

        // Asset valuations
        $goldValue = $goldGrams * $goldPrice;
        $silverValue = $silverGrams * $silverPrice;

        $totalAssets = $cash + $goldValue + $silverValue + $businessAssets + $receivables + $investments;
        $netAssets = max(0, $totalAssets - $liabilities);

        // Nisab calculation
        $nisabValue = $basis === 'gold'
            ? self::NISAB_GOLD_GRAMS * $goldPrice
            : self::NISAB_SILVER_GRAMS * $silverPrice;

        $isAboveNisab = $netAssets >= $nisabValue;
        $zakatDue = $isAboveNisab ? round($netAssets * $rate, 2) : 0.0;

        return [
            'success' => true,
            'currency' => $currency,
            'year' => $year,
            'basis' => $basis,
            'nisab_value' => round($nisabValue, 2),
            'is_above_nisab' => $isAboveNisab,
            'rate_percent' => $rate * 100,
            'totals' => [
                'cash' => round($cash, 2),
                'gold_value' => round($goldValue, 2),
                'silver_value' => round($silverValue, 2),
                'business_assets' => round($businessAssets, 2),
                'receivables' => round($receivables, 2),
                'investments' => round($investments, 2),
                'liabilities' => round($liabilities, 2),
                'total_assets' => round($totalAssets, 2),
                'net_assets' => round($netAssets, 2),
            ],
            'zakat_due' => round($zakatDue, 2),
        ];
    }
}
