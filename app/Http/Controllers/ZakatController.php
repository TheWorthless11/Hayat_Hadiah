<?php

namespace App\Http\Controllers;

use App\Services\ZakatService;
use App\Models\ZakatRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZakatController extends Controller
{
    public function index()
    {
        // Defaults (user can override in form)
        $defaults = [
            'gold_price_per_gram' => 8500.0,  // TK, approximate; user can change
            'silver_price_per_gram' => 120.0, // TK, approximate; user can change
            'basis' => 'gold',                // gold basis (85g) by default
            'zakat_rate' => 2.5,              // %
            'currency' => 'TK',
            'year' => now()->year,
        ];

        return view('zakat.index', compact('defaults'));
    }

    public function calculate(Request $request, ZakatService $service)
    {
        $data = $request->validate([
            'cash' => 'nullable|numeric|min:0',
            'gold_grams' => 'nullable|numeric|min:0',
            'gold_carat' => 'nullable|numeric|in:10,14,18,21,22,24',
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

    public function save(Request $request, ZakatService $service)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Authentication required'], 401);
        }

        $data = $request->validate([
            'cash' => 'nullable|numeric|min:0',
            'gold_grams' => 'nullable|numeric|min:0',
            'gold_carat' => 'nullable|numeric|in:10,14,18,21,22,24',
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
            'notes' => 'nullable|string|max:500',
        ]);

        $result = $service->calculate($data);

        $record = ZakatRecord::create([
            'user_id' => Auth::id(),
            'zakat_category_id' => null, // can be set if using category dropdown
            'assets_value' => $result['totals']['total_assets'],
            'liabilities_value' => $result['totals']['liabilities'],
            'zakat_due' => $result['zakat_due'],
            'calculation_year' => $result['year'],
            'notes' => $data['notes'] ?? null,
            'breakdown' => $result,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Zakat calculation saved successfully',
            'record' => $record,
        ]);
    }
}
