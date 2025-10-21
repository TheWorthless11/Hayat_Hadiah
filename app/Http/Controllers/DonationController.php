<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationCategory;
use App\Mail\DonationReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DonationController extends Controller
{
    /**
     * Display the donation form
     */
    public function index()
    {
        // Get categories with goal tracking data
        $categories = DonationCategory::active()
            ->orderBy('name')
            ->get();
        
        // Get categories with active goals for progress display
        $categoriesWithGoals = $categories->filter(function($category) {
            return $category->goal_amount && $category->goal_amount > 0;
        });
        
        // Random Quran verses about charity
        $verses = [
            [
                'text' => 'Those who spend their wealth in the cause of Allah will be rewarded multiplied.',
                'reference' => 'Qur\'an 2:261'
            ],
            [
                'text' => 'The example of those who spend their wealth seeking Allah\'s pleasure is like a garden on a hill.',
                'reference' => 'Qur\'an 2:265'
            ],
            [
                'text' => 'Whatever you spend in good is for yourselves, and you do not spend except seeking the countenance of Allah.',
                'reference' => 'Qur\'an 2:272'
            ],
            [
                'text' => 'Believe in Allah and His Messenger and spend out of that in which He has made you successors.',
                'reference' => 'Qur\'an 57:7'
            ],
            [
                'text' => 'Charity does not decrease wealth.',
                'reference' => 'Hadith - Sahih Muslim'
            ]
        ];
        
        $randomVerse = $verses[array_rand($verses)];
        
        return view('donations.index', compact('categories', 'categoriesWithGoals', 'randomVerse'));
    }

    /**
     * Process the donation and initiate payment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email|max:255',
            'donor_phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:10|max:1000000',
            'category_id' => 'required|exists:donation_categories,id',
            'message' => 'nullable|string|max:500',
            'payment_method' => 'required|in:bkash,nagad',
        ]);

        try {
            // Generate unique transaction reference
            $transactionRef = Donation::generateTransactionRef();

            // Create donation record
            $donation = Donation::create([
                'transaction_ref' => $transactionRef,
                'donor_name' => $validated['donor_name'],
                'donor_email' => $validated['donor_email'],
                'donor_phone' => $validated['donor_phone'],
                'amount' => $validated['amount'],
                'currency' => 'BDT',
                'category_id' => $validated['category_id'],
                'message' => $validated['message'] ?? null,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'user_id' => Auth::id(),
            ]);

            // Initiate payment based on method
            if ($validated['payment_method'] === 'bkash') {
                return $this->initiateBkashPayment($donation);
            } else {
                return $this->initiateNagadPayment($donation);
            }

        } catch (\Exception $e) {
            Log::error('Donation creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to process donation. Please try again.'])->withInput();
        }
    }

    /**
     * Initiate bKash sandbox payment
     */
    private function initiateBkashPayment($donation)
    {
        // TODO: Implement bKash sandbox API integration
        // For now, simulate successful payment
        
        // Simulate payment processing
        $donation->update([
            'payment_status' => 'success',
            'payment_transaction_id' => 'BKASH-' . strtoupper(substr(md5(uniqid()), 0, 10)),
            'payment_response' => [
                'method' => 'bkash',
                'status' => 'success',
                'timestamp' => now()->toIso8601String(),
            ]
        ]);

        // Send receipt email
        $this->sendReceiptEmail($donation);

        return redirect()->route('donations.thank-you', ['transaction' => $donation->transaction_ref]);
    }

    /**
     * Initiate Nagad sandbox payment
     */
    private function initiateNagadPayment($donation)
    {
        // TODO: Implement Nagad sandbox API integration
        // For now, simulate successful payment
        
        // Simulate payment processing
        $donation->update([
            'payment_status' => 'success',
            'payment_transaction_id' => 'NAGAD-' . strtoupper(substr(md5(uniqid()), 0, 10)),
            'payment_response' => [
                'method' => 'nagad',
                'status' => 'success',
                'timestamp' => now()->toIso8601String(),
            ]
        ]);

        // Send receipt email
        $this->sendReceiptEmail($donation);

        return redirect()->route('donations.thank-you', ['transaction' => $donation->transaction_ref]);
    }

    /**
     * Send receipt email to donor
     */
    private function sendReceiptEmail($donation)
    {
        try {
            Mail::to($donation->donor_email)->send(new DonationReceipt($donation));
            Log::info('Receipt email sent to: ' . $donation->donor_email);
        } catch (\Exception $e) {
            Log::error('Failed to send receipt email: ' . $e->getMessage());
            // Don't stop the process if email fails
        }
    }

    /**
     * Display thank you page after successful donation
     */
    public function thankYou(Request $request)
    {
        $donation = Donation::where('transaction_ref', $request->transaction)->firstOrFail();
        
        return view('donations.thank-you', compact('donation'));
    }

    /**
     * Handle payment callback from bKash/Nagad
     */
    public function paymentCallback(Request $request)
    {
        // TODO: Handle actual payment gateway callbacks
        // Verify payment status and update donation record
        
        Log::info('Payment callback received', $request->all());
        
        return response()->json(['status' => 'received']);
    }
}
