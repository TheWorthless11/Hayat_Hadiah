<?php

namespace App\Http\Controllers;

use App\Services\ZakatService;
use Illuminate\Http\Request;

class ZakatController extends Controller
{
    public function index()
    {
        // Defaults (user can override in form)
        $defaults = [
            'gold_price_per_gram' => 75.0,   // USD, approximate; user can change
            'silver_price_per_gram' => 1.0,  // USD, approximate; user can change
            'basis' => 'gold',               // gold basis (85g) by default
            'zakat_rate' => 2.5,             // %
            'currency' => 'USD',
            'year' => now()->year,
        ];

        return view('zakat.index', compact('defaults'));
    }

    public function calculate(Request $request, ZakatService $service)
    {
        $data = $request->validate([
            'cash' => 'nullable|numeric|min:0',
            'gold_grams' => 'nullable|numeric|min:0',
            'silver_grams' => 'nullable|numeric|min:0',
            'business_assets' => 'nullable|numeric|min:0',
            'receivables' => 'nullable|numeric|min:0',
            'investments' => 'nullable|numeric|min:0',
            'liabilities' => 'nullable|numeric|min:0',
            'gold_price_per_gram' => 'required|numeric|min:0',
            'silver_price_per_gram' => 'required|numeric|min:0',
            'basis' => 'required|in:gold,silver',
            'zakat_rate' => 'required|numeric|min:0|max:100',
            'currency' => 'nullable|string|max:8',
            'year' => 'nullable|integer|min:1300|max:3000',
        ]);

        $result = $service->calculate($data);

        return response()->json($result);
    }
}
