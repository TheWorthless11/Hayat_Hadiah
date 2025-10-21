<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDonationController extends Controller
{
    /**
     * Display admin donation dashboard
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        $category = $request->input('category');
        $paymentMethod = $request->input('payment_method');
        $status = $request->input('status');

        // Build query
        $query = Donation::with(['category', 'user'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        }

        if ($category) {
            $query->where('category_id', $category);
        }

        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }

        if ($status) {
            $query->where('payment_status', $status);
        }

        // Get paginated donations
        $donations = $query->paginate(20);

        // Calculate summary statistics
        $totalDonations = Donation::successful()->sum('amount');
        $thisMonthTotal = Donation::successful()
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');
        $totalTransactions = Donation::count();
        $pendingCount = Donation::pending()->count();
        $successRate = $totalTransactions > 0 
            ? round((Donation::successful()->count() / $totalTransactions) * 100, 2)
            : 0;

        // Get donations by category for chart
        $donationsByCategory = Donation::successful()
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category->name,
                    'total' => $item->total
                ];
            });

        // Get monthly trend (last 6 months)
        $monthlyTrend = Donation::successful()
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get all categories for filter dropdown
        $categories = DonationCategory::active()->orderBy('name')->get();

        return view('admin.donations.index', compact(
            'donations',
            'totalDonations',
            'thisMonthTotal',
            'totalTransactions',
            'pendingCount',
            'successRate',
            'donationsByCategory',
            'monthlyTrend',
            'categories'
        ));
    }

    /**
     * Export donations to CSV
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Donation::with(['category', 'user'])
            ->orderBy('created_at', 'desc');

        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        }

        $donations = $query->get();

        $filename = 'donations_' . date('Ymd_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($donations) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Transaction Ref',
                'Date',
                'Donor Name',
                'Email',
                'Phone',
                'Amount (BDT)',
                'Category',
                'Payment Method',
                'Status',
                'Message'
            ]);

            // Data rows
            foreach ($donations as $donation) {
                fputcsv($file, [
                    $donation->transaction_ref,
                    $donation->created_at->format('Y-m-d H:i:s'),
                    $donation->donor_name,
                    $donation->donor_email,
                    $donation->donor_phone,
                    $donation->amount,
                    $donation->category->name,
                    ucfirst($donation->payment_method),
                    ucfirst($donation->payment_status),
                    $donation->message ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Manage donation categories
     */
    public function categories()
    {
        $categories = DonationCategory::withCount('donations')
            ->orderBy('name')
            ->get();

        return view('admin.donations.categories', compact('categories'));
    }

    /**
     * Store new category
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:donation_categories,name',
            'description' => 'nullable|string|max:500',
            'goal_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'is_active' => 'boolean',
        ]);

        DonationCategory::create($validated);

        return back()->with('success', 'Category created successfully.');
    }

    /**
     * Update category
     */
    public function updateCategory(Request $request, DonationCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:donation_categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'goal_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return back()->with('success', 'Category updated successfully.');
    }

    /**
     * Update goal for a category
     */
    public function updateGoal(Request $request, DonationCategory $category)
    {
        $validated = $request->validate([
            'goal_amount' => 'required|numeric|min:0|max:99999999.99',
        ]);

        $category->update(['goal_amount' => $validated['goal_amount']]);

        return response()->json([
            'success' => true,
            'message' => 'Goal updated successfully.',
            'category' => $category
        ]);
    }

    /**
     * Delete category
     */
    public function destroyCategory(DonationCategory $category)
    {
        if ($category->donations()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete category with existing donations.']);
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }
}
