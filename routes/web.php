<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\PrayerController;
use App\Http\Controllers\QiblaController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\HadithController;
use App\Http\Controllers\FastingController;

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
