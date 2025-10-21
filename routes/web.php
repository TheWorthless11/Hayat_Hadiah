<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PrayerController;
use App\Http\Controllers\QiblaController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\HadithController;
use App\Http\Controllers\FastingController;
use App\Http\Controllers\ZakatController;
use App\Http\Controllers\MosqueController;
use App\Http\Controllers\DuaController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\AdminDonationController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- PUBLIC MODULE ROUTES ---

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Prayer Times Module
Route::get('/prayers', [PrayerController::class, 'index'])->name('prayers.index');

// Qibla Compass Module
Route::get('/qibla', [QiblaController::class, 'index'])->name('qibla.index');
Route::post('/qibla/calculate', [QiblaController::class, 'calculate'])->name('qibla.calculate');
Route::post('/qibla/save', [QiblaController::class, 'saveLocation'])->name('qibla.save');
Route::get('/qibla/saved', [QiblaController::class, 'getSavedLocations'])->name('qibla.saved');
Route::get('/qibla/load/{id}', [QiblaController::class, 'loadLocation'])->name('qibla.load');
Route::delete('/qibla/saved/{id}', [QiblaController::class, 'deleteSavedLocation'])->name('qibla.delete');
Route::post('/qibla/favorite/{id}', [QiblaController::class, 'toggleFavorite'])->name('qibla.favorite');

// Quran Module
Route::get('/quran', [QuranController::class, 'index'])->name('quran.index');
Route::get('/quran/surah/{surah}', [QuranController::class, 'getSurah'])->name('quran.surah');
Route::get('/quran/verse/{surah}/{verse}', [QuranController::class, 'getVerse'])->name('quran.verse');
Route::get('/quran/search', [QuranController::class, 'search'])->name('quran.search');
Route::get('/quran/verse-of-day', [QuranController::class, 'verseOfTheDay'])->name('quran.verse-of-day');

// Hadith Module
Route::get('/hadith', [HadithController::class, 'index'])->name('hadith.index');
Route::get('/hadith/collection/{collection}', [HadithController::class, 'getCollection'])->name('hadith.collection');
Route::get('/hadith/{id}', [HadithController::class, 'getHadith'])->name('hadith.show');
Route::get('/hadith-search', [HadithController::class, 'search'])->name('hadith.search');
Route::get('/hadith-of-day', [HadithController::class, 'hadithOfTheDay'])->name('hadith.of-day');
Route::get('/hadith-random', [HadithController::class, 'random'])->name('hadith.random');

// Fasting Module
Route::get('/fasting', [FastingController::class, 'index'])->name('fasting.index');
Route::post('/fasting/generate', [FastingController::class, 'generate'])->name('fasting.generate');

// Zakat Module
Route::get('/zakat', [ZakatController::class, 'index'])->name('zakat.index');
Route::post('/zakat/calculate', [ZakatController::class, 'calculate'])->name('zakat.calculate');
Route::post('/zakat/save', [ZakatController::class, 'save'])->name('zakat.save');

// Nearby Mosque Module
Route::get('/mosques', [MosqueController::class, 'index'])->name('mosques.index');
Route::post('/mosques/nearby', [MosqueController::class, 'fetchNearby'])->name('mosques.nearby');

// Duas Module
Route::get('/duas', [DuaController::class, 'index'])->name('duas.index');
Route::post('/duas', [DuaController::class, 'store'])->name('duas.store');
Route::put('/duas/{dua}', [DuaController::class, 'update'])->name('duas.update');
Route::delete('/duas/{dua}', [DuaController::class, 'destroy'])->name('duas.destroy');

// Donation Module (Public)
Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
Route::get('/donations/thank-you', [DonationController::class, 'thankYou'])->name('donations.thank-you');
Route::post('/donations/payment/callback', [DonationController::class, 'paymentCallback'])->name('donations.callback');

// Chatbot Module
Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');


// --- AUTHENTICATION & USER ROUTES ---

// Profile Routes (from Breeze) - Requires user to be logged in
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- ADMIN-ONLY ROUTES ---

// Requires user to be logged in AND be an admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Your Original Admin Donation Routes (now protected)
    Route::prefix('donations')->name('donations.')->group(function () {
        Route::get('/', [AdminDonationController::class, 'index'])->name('index');
        Route::get('/export', [AdminDonationController::class, 'export'])->name('export');
        Route::get('/categories', [AdminDonationController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminDonationController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [AdminDonationController::class, 'updateCategory'])->name('categories.update');
        Route::put('/categories/{category}/goal', [AdminDonationController::class, 'updateGoal'])->name('categories.update-goal');
        Route::delete('/categories/{category}', [AdminDonationController::class, 'destroyCategory'])->name('categories.destroy');
    });

    // You can add more admin-only routes here in the future
});


// Auth Routes (Login, Register, Logout, etc. from Breeze)
require __DIR__.'/auth.php';
