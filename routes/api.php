<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatbotApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Chatbot API Routes
Route::prefix('chatbot')->group(function () {
    // Prayer Times
    Route::get('/prayer-times', [ChatbotApiController::class, 'getPrayerTimes']);
    Route::get('/prayer-times/{prayerName}', [ChatbotApiController::class, 'getSpecificPrayer']);
    
    // Qibla Direction
    Route::get('/qibla-direction', [ChatbotApiController::class, 'getQiblaDirection']);
    
    // Quran
    Route::get('/quran-verse', [ChatbotApiController::class, 'getQuranVerse']);
    Route::get('/quran-verse/random', [ChatbotApiController::class, 'getRandomQuranVerse']);
    
    // Hadith
    Route::get('/hadith', [ChatbotApiController::class, 'getHadith']);
    Route::get('/hadith/random', [ChatbotApiController::class, 'getRandomHadith']);
    
    // Duas
    Route::get('/duas', [ChatbotApiController::class, 'getDuas']);
    Route::get('/duas/{category}', [ChatbotApiController::class, 'getSpecificDua']);
    
    // Zakat
    Route::get('/zakat-info', [ChatbotApiController::class, 'getZakatInfo']);
    
    // Donations
    Route::get('/donations/info', [ChatbotApiController::class, 'getDonationInfo']);
    
    // Mosques
    Route::get('/mosques/nearby', [ChatbotApiController::class, 'findNearbyMosques']);
    
    // Fasting
    Route::get('/fasting-times', [ChatbotApiController::class, 'getFastingTimes']);
    Route::get('/ramadan-info', [ChatbotApiController::class, 'getRamadanInfo']);
});
