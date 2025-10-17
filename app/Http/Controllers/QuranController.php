<?php

namespace App\Http\Controllers;

use App\Models\QuranVerse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QuranController extends Controller
{
    /**
     * Display the Quran reader interface
     */
    public function index()
    {
        // Get list of Surahs (114 surahs in Quran)
        $surahs = $this->getSurahList();
        
        return view('quran.index', compact('surahs'));
    }

    /**
     * Get a specific Surah with all its verses
     */
    public function getSurah(Request $request, $surahNumber)
    {
        $request->validate([
            'language' => 'nullable|string|in:en,ar,bn'
        ]);

        $language = $request->input('language', 'en');

        // Check if verses exist in database
        $verses = QuranVerse::where('surah', $surahNumber)
            ->where('language', $language)
            ->orderBy('ayah')
            ->get();

        if ($verses->isEmpty()) {
            // Fetch from API and store
            $verses = $this->fetchAndStoreFromAPI($surahNumber, $language);
        }

        $surahInfo = $this->getSurahInfo($surahNumber);

        return response()->json([
            'success' => true,
            'surah' => $surahInfo,
            'verses' => $verses
        ]);
    }

    /**
     * Get a specific verse
     */
    public function getVerse(Request $request, $surahNumber, $verseNumber)
    {
        $language = $request->input('language', 'en');

        $verse = QuranVerse::where('surah', $surahNumber)
            ->where('ayah', $verseNumber)
            ->where('language', $language)
            ->first();

        if (!$verse) {
            return response()->json([
                'success' => false,
                'message' => 'Verse not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'verse' => $verse
        ]);
    }

    /**
     * Search verses by keyword
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3',
            'language' => 'nullable|string|in:en,ar,bn'
        ]);

        $query = $request->input('query');
        $language = $request->input('language', 'en');

        $verses = QuranVerse::where('language', $language)
            ->where(function($q) use ($query) {
                $q->where('arabic_text', 'LIKE', "%{$query}%")
                  ->orWhere('translation', 'LIKE', "%{$query}%")
                  ->orWhere('transliteration', 'LIKE', "%{$query}%");
            })
            ->orderBy('surah')
            ->orderBy('ayah')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'results' => $verses,
            'count' => $verses->count()
        ]);
    }

    /**
     * Get verse of the day
     */
    public function verseOfTheDay()
    {
        // Generate a consistent random verse for today
        $seed = date('Ymd');
        mt_srand($seed);
        
        // Random surah and ayah
        $surah = mt_rand(1, 114);
        $maxAyah = $this->getMaxAyahForSurah($surah);
        $ayah = mt_rand(1, $maxAyah);

        $verse = QuranVerse::where('surah', $surah)
            ->where('ayah', $ayah)
            ->where('language', 'en')
            ->first();

        if (!$verse) {
            // Fallback to Al-Fatiha 1:1
            $verse = QuranVerse::where('surah', 1)
                ->where('ayah', 1)
                ->where('language', 'en')
                ->first();
        }

        $surahInfo = $this->getSurahInfo($verse->surah);

        return response()->json([
            'success' => true,
            'verse' => $verse,
            'surah_info' => $surahInfo
        ]);
    }

    /**
     * Fetch verses from API and store in database
     */
    private function fetchAndStoreFromAPI($surahNumber, $language = 'en')
    {
        // This is a placeholder - you'll need to implement API integration
        // Popular APIs: api.quran.com, alquran.cloud, quran.api-docs.io
        
        // For now, return empty collection
        // You can integrate with a real API later
        return collect([]);
    }

    /**
     * Get list of all Surahs (Complete list of 114 Surahs)
     */
    private function getSurahList()
    {
        return [
            ['number' => 1, 'name' => 'Al-Fatihah', 'translation' => 'The Opening', 'verses' => 7, 'revelation' => 'Meccan'],
            ['number' => 2, 'name' => 'Al-Baqarah', 'translation' => 'The Cow', 'verses' => 286, 'revelation' => 'Medinan'],
            ['number' => 3, 'name' => 'Ali \'Imran', 'translation' => 'Family of Imran', 'verses' => 200, 'revelation' => 'Medinan'],
            ['number' => 4, 'name' => 'An-Nisa', 'translation' => 'The Women', 'verses' => 176, 'revelation' => 'Medinan'],
            ['number' => 5, 'name' => 'Al-Ma\'idah', 'translation' => 'The Table Spread', 'verses' => 120, 'revelation' => 'Medinan'],
            ['number' => 6, 'name' => 'Al-An\'am', 'translation' => 'The Cattle', 'verses' => 165, 'revelation' => 'Meccan'],
            ['number' => 7, 'name' => 'Al-A\'raf', 'translation' => 'The Heights', 'verses' => 206, 'revelation' => 'Meccan'],
            ['number' => 8, 'name' => 'Al-Anfal', 'translation' => 'The Spoils of War', 'verses' => 75, 'revelation' => 'Medinan'],
            ['number' => 9, 'name' => 'At-Tawbah', 'translation' => 'The Repentance', 'verses' => 129, 'revelation' => 'Medinan'],
            ['number' => 10, 'name' => 'Yunus', 'translation' => 'Jonah', 'verses' => 109, 'revelation' => 'Meccan'],
            ['number' => 11, 'name' => 'Hud', 'translation' => 'Hud', 'verses' => 123, 'revelation' => 'Meccan'],
            ['number' => 12, 'name' => 'Yusuf', 'translation' => 'Joseph', 'verses' => 111, 'revelation' => 'Meccan'],
            ['number' => 13, 'name' => 'Ar-Ra\'d', 'translation' => 'The Thunder', 'verses' => 43, 'revelation' => 'Medinan'],
            ['number' => 14, 'name' => 'Ibrahim', 'translation' => 'Abraham', 'verses' => 52, 'revelation' => 'Meccan'],
            ['number' => 15, 'name' => 'Al-Hijr', 'translation' => 'The Rocky Tract', 'verses' => 99, 'revelation' => 'Meccan'],
            ['number' => 16, 'name' => 'An-Nahl', 'translation' => 'The Bee', 'verses' => 128, 'revelation' => 'Meccan'],
            ['number' => 17, 'name' => 'Al-Isra', 'translation' => 'The Night Journey', 'verses' => 111, 'revelation' => 'Meccan'],
            ['number' => 18, 'name' => 'Al-Kahf', 'translation' => 'The Cave', 'verses' => 110, 'revelation' => 'Meccan'],
            ['number' => 19, 'name' => 'Maryam', 'translation' => 'Mary', 'verses' => 98, 'revelation' => 'Meccan'],
            ['number' => 20, 'name' => 'Taha', 'translation' => 'Ta-Ha', 'verses' => 135, 'revelation' => 'Meccan'],
            ['number' => 21, 'name' => 'Al-Anbya', 'translation' => 'The Prophets', 'verses' => 112, 'revelation' => 'Meccan'],
            ['number' => 22, 'name' => 'Al-Hajj', 'translation' => 'The Pilgrimage', 'verses' => 78, 'revelation' => 'Medinan'],
            ['number' => 23, 'name' => 'Al-Mu\'minun', 'translation' => 'The Believers', 'verses' => 118, 'revelation' => 'Meccan'],
            ['number' => 24, 'name' => 'An-Nur', 'translation' => 'The Light', 'verses' => 64, 'revelation' => 'Medinan'],
            ['number' => 25, 'name' => 'Al-Furqan', 'translation' => 'The Criterion', 'verses' => 77, 'revelation' => 'Meccan'],
            ['number' => 26, 'name' => 'Ash-Shu\'ara', 'translation' => 'The Poets', 'verses' => 227, 'revelation' => 'Meccan'],
            ['number' => 27, 'name' => 'An-Naml', 'translation' => 'The Ant', 'verses' => 93, 'revelation' => 'Meccan'],
            ['number' => 28, 'name' => 'Al-Qasas', 'translation' => 'The Stories', 'verses' => 88, 'revelation' => 'Meccan'],
            ['number' => 29, 'name' => 'Al-\'Ankabut', 'translation' => 'The Spider', 'verses' => 69, 'revelation' => 'Meccan'],
            ['number' => 30, 'name' => 'Ar-Rum', 'translation' => 'The Romans', 'verses' => 60, 'revelation' => 'Meccan'],
            ['number' => 31, 'name' => 'Luqman', 'translation' => 'Luqman', 'verses' => 34, 'revelation' => 'Meccan'],
            ['number' => 32, 'name' => 'As-Sajdah', 'translation' => 'The Prostration', 'verses' => 30, 'revelation' => 'Meccan'],
            ['number' => 33, 'name' => 'Al-Ahzab', 'translation' => 'The Combined Forces', 'verses' => 73, 'revelation' => 'Medinan'],
            ['number' => 34, 'name' => 'Saba', 'translation' => 'Sheba', 'verses' => 54, 'revelation' => 'Meccan'],
            ['number' => 35, 'name' => 'Fatir', 'translation' => 'The Originator', 'verses' => 45, 'revelation' => 'Meccan'],
            ['number' => 36, 'name' => 'Ya-Sin', 'translation' => 'Ya Sin', 'verses' => 83, 'revelation' => 'Meccan'],
            ['number' => 37, 'name' => 'As-Saffat', 'translation' => 'Those Ranges in Ranks', 'verses' => 182, 'revelation' => 'Meccan'],
            ['number' => 38, 'name' => 'Sad', 'translation' => 'The Letter Sad', 'verses' => 88, 'revelation' => 'Meccan'],
            ['number' => 39, 'name' => 'Az-Zumar', 'translation' => 'The Groups', 'verses' => 75, 'revelation' => 'Meccan'],
            ['number' => 40, 'name' => 'Ghafir', 'translation' => 'The Forgiver', 'verses' => 85, 'revelation' => 'Meccan'],
            ['number' => 41, 'name' => 'Fussilat', 'translation' => 'Explained in Detail', 'verses' => 54, 'revelation' => 'Meccan'],
            ['number' => 42, 'name' => 'Ash-Shuraa', 'translation' => 'The Consultation', 'verses' => 53, 'revelation' => 'Meccan'],
            ['number' => 43, 'name' => 'Az-Zukhruf', 'translation' => 'The Ornaments of Gold', 'verses' => 89, 'revelation' => 'Meccan'],
            ['number' => 44, 'name' => 'Ad-Dukhan', 'translation' => 'The Smoke', 'verses' => 59, 'revelation' => 'Meccan'],
            ['number' => 45, 'name' => 'Al-Jathiyah', 'translation' => 'The Crouching', 'verses' => 37, 'revelation' => 'Meccan'],
            ['number' => 46, 'name' => 'Al-Ahqaf', 'translation' => 'The Wind-Curved Sandhills', 'verses' => 35, 'revelation' => 'Meccan'],
            ['number' => 47, 'name' => 'Muhammad', 'translation' => 'Muhammad', 'verses' => 38, 'revelation' => 'Medinan'],
            ['number' => 48, 'name' => 'Al-Fath', 'translation' => 'The Victory', 'verses' => 29, 'revelation' => 'Medinan'],
            ['number' => 49, 'name' => 'Al-Hujurat', 'translation' => 'The Rooms', 'verses' => 18, 'revelation' => 'Medinan'],
            ['number' => 50, 'name' => 'Qaf', 'translation' => 'The Letter Qaf', 'verses' => 45, 'revelation' => 'Meccan'],
            ['number' => 51, 'name' => 'Adh-Dhariyat', 'translation' => 'The Winnowing Winds', 'verses' => 60, 'revelation' => 'Meccan'],
            ['number' => 52, 'name' => 'At-Tur', 'translation' => 'The Mount', 'verses' => 49, 'revelation' => 'Meccan'],
            ['number' => 53, 'name' => 'An-Najm', 'translation' => 'The Star', 'verses' => 62, 'revelation' => 'Meccan'],
            ['number' => 54, 'name' => 'Al-Qamar', 'translation' => 'The Moon', 'verses' => 55, 'revelation' => 'Meccan'],
            ['number' => 55, 'name' => 'Ar-Rahman', 'translation' => 'The Most Merciful', 'verses' => 78, 'revelation' => 'Medinan'],
            ['number' => 56, 'name' => 'Al-Waqi\'ah', 'translation' => 'The Inevitable', 'verses' => 96, 'revelation' => 'Meccan'],
            ['number' => 57, 'name' => 'Al-Hadid', 'translation' => 'The Iron', 'verses' => 29, 'revelation' => 'Medinan'],
            ['number' => 58, 'name' => 'Al-Mujadila', 'translation' => 'The Pleading Woman', 'verses' => 22, 'revelation' => 'Medinan'],
            ['number' => 59, 'name' => 'Al-Hashr', 'translation' => 'The Exile', 'verses' => 24, 'revelation' => 'Medinan'],
            ['number' => 60, 'name' => 'Al-Mumtahanah', 'translation' => 'She That is to be Examined', 'verses' => 13, 'revelation' => 'Medinan'],
            ['number' => 61, 'name' => 'As-Saf', 'translation' => 'The Ranks', 'verses' => 14, 'revelation' => 'Medinan'],
            ['number' => 62, 'name' => 'Al-Jumu\'ah', 'translation' => 'Friday', 'verses' => 11, 'revelation' => 'Medinan'],
            ['number' => 63, 'name' => 'Al-Munafiqun', 'translation' => 'The Hypocrites', 'verses' => 11, 'revelation' => 'Medinan'],
            ['number' => 64, 'name' => 'At-Taghabun', 'translation' => 'The Mutual Disillusion', 'verses' => 18, 'revelation' => 'Medinan'],
            ['number' => 65, 'name' => 'At-Talaq', 'translation' => 'The Divorce', 'verses' => 12, 'revelation' => 'Medinan'],
            ['number' => 66, 'name' => 'At-Tahrim', 'translation' => 'The Prohibition', 'verses' => 12, 'revelation' => 'Medinan'],
            ['number' => 67, 'name' => 'Al-Mulk', 'translation' => 'The Sovereignty', 'verses' => 30, 'revelation' => 'Meccan'],
            ['number' => 68, 'name' => 'Al-Qalam', 'translation' => 'The Pen', 'verses' => 52, 'revelation' => 'Meccan'],
            ['number' => 69, 'name' => 'Al-Haqqah', 'translation' => 'The Reality', 'verses' => 52, 'revelation' => 'Meccan'],
            ['number' => 70, 'name' => 'Al-Ma\'arij', 'translation' => 'The Ascending Stairways', 'verses' => 44, 'revelation' => 'Meccan'],
            ['number' => 71, 'name' => 'Nuh', 'translation' => 'Noah', 'verses' => 28, 'revelation' => 'Meccan'],
            ['number' => 72, 'name' => 'Al-Jinn', 'translation' => 'The Jinn', 'verses' => 28, 'revelation' => 'Meccan'],
            ['number' => 73, 'name' => 'Al-Muzzammil', 'translation' => 'The Enshrouded One', 'verses' => 20, 'revelation' => 'Meccan'],
            ['number' => 74, 'name' => 'Al-Muddaththir', 'translation' => 'The Cloaked One', 'verses' => 56, 'revelation' => 'Meccan'],
            ['number' => 75, 'name' => 'Al-Qiyamah', 'translation' => 'The Resurrection', 'verses' => 40, 'revelation' => 'Meccan'],
            ['number' => 76, 'name' => 'Al-Insan', 'translation' => 'The Man', 'verses' => 31, 'revelation' => 'Medinan'],
            ['number' => 77, 'name' => 'Al-Mursalat', 'translation' => 'The Emissaries', 'verses' => 50, 'revelation' => 'Meccan'],
            ['number' => 78, 'name' => 'An-Naba', 'translation' => 'The Tidings', 'verses' => 40, 'revelation' => 'Meccan'],
            ['number' => 79, 'name' => 'An-Nazi\'at', 'translation' => 'Those Who Drag Forth', 'verses' => 46, 'revelation' => 'Meccan'],
            ['number' => 80, 'name' => 'Abasa', 'translation' => 'He Frowned', 'verses' => 42, 'revelation' => 'Meccan'],
            ['number' => 81, 'name' => 'At-Takwir', 'translation' => 'The Overthrowing', 'verses' => 29, 'revelation' => 'Meccan'],
            ['number' => 82, 'name' => 'Al-Infitar', 'translation' => 'The Cleaving', 'verses' => 19, 'revelation' => 'Meccan'],
            ['number' => 83, 'name' => 'Al-Mutaffifin', 'translation' => 'The Defrauding', 'verses' => 36, 'revelation' => 'Meccan'],
            ['number' => 84, 'name' => 'Al-Inshiqaq', 'translation' => 'The Splitting Open', 'verses' => 25, 'revelation' => 'Meccan'],
            ['number' => 85, 'name' => 'Al-Buruj', 'translation' => 'The Mansions of the Stars', 'verses' => 22, 'revelation' => 'Meccan'],
            ['number' => 86, 'name' => 'At-Tariq', 'translation' => 'The Morning Star', 'verses' => 17, 'revelation' => 'Meccan'],
            ['number' => 87, 'name' => 'Al-A\'la', 'translation' => 'The Most High', 'verses' => 19, 'revelation' => 'Meccan'],
            ['number' => 88, 'name' => 'Al-Ghashiyah', 'translation' => 'The Overwhelming', 'verses' => 26, 'revelation' => 'Meccan'],
            ['number' => 89, 'name' => 'Al-Fajr', 'translation' => 'The Dawn', 'verses' => 30, 'revelation' => 'Meccan'],
            ['number' => 90, 'name' => 'Al-Balad', 'translation' => 'The City', 'verses' => 20, 'revelation' => 'Meccan'],
            ['number' => 91, 'name' => 'Ash-Shams', 'translation' => 'The Sun', 'verses' => 15, 'revelation' => 'Meccan'],
            ['number' => 92, 'name' => 'Al-Layl', 'translation' => 'The Night', 'verses' => 21, 'revelation' => 'Meccan'],
            ['number' => 93, 'name' => 'Ad-Duhaa', 'translation' => 'The Morning Hours', 'verses' => 11, 'revelation' => 'Meccan'],
            ['number' => 94, 'name' => 'Ash-Sharh', 'translation' => 'The Relief', 'verses' => 8, 'revelation' => 'Meccan'],
            ['number' => 95, 'name' => 'At-Tin', 'translation' => 'The Fig', 'verses' => 8, 'revelation' => 'Meccan'],
            ['number' => 96, 'name' => 'Al-\'Alaq', 'translation' => 'The Clot', 'verses' => 19, 'revelation' => 'Meccan'],
            ['number' => 97, 'name' => 'Al-Qadr', 'translation' => 'The Power', 'verses' => 5, 'revelation' => 'Meccan'],
            ['number' => 98, 'name' => 'Al-Bayyinah', 'translation' => 'The Clear Proof', 'verses' => 8, 'revelation' => 'Medinan'],
            ['number' => 99, 'name' => 'Az-Zalzalah', 'translation' => 'The Earthquake', 'verses' => 8, 'revelation' => 'Medinan'],
            ['number' => 100, 'name' => 'Al-\'Adiyat', 'translation' => 'The Courser', 'verses' => 11, 'revelation' => 'Meccan'],
            ['number' => 101, 'name' => 'Al-Qari\'ah', 'translation' => 'The Calamity', 'verses' => 11, 'revelation' => 'Meccan'],
            ['number' => 102, 'name' => 'At-Takathur', 'translation' => 'The Rivalry in World Increase', 'verses' => 8, 'revelation' => 'Meccan'],
            ['number' => 103, 'name' => 'Al-\'Asr', 'translation' => 'The Declining Day', 'verses' => 3, 'revelation' => 'Meccan'],
            ['number' => 104, 'name' => 'Al-Humazah', 'translation' => 'The Traducer', 'verses' => 9, 'revelation' => 'Meccan'],
            ['number' => 105, 'name' => 'Al-Fil', 'translation' => 'The Elephant', 'verses' => 5, 'revelation' => 'Meccan'],
            ['number' => 106, 'name' => 'Quraysh', 'translation' => 'Quraysh', 'verses' => 4, 'revelation' => 'Meccan'],
            ['number' => 107, 'name' => 'Al-Ma\'un', 'translation' => 'The Small Kindnesses', 'verses' => 7, 'revelation' => 'Meccan'],
            ['number' => 108, 'name' => 'Al-Kawthar', 'translation' => 'The Abundance', 'verses' => 3, 'revelation' => 'Meccan'],
            ['number' => 109, 'name' => 'Al-Kafirun', 'translation' => 'The Disbelievers', 'verses' => 6, 'revelation' => 'Meccan'],
            ['number' => 110, 'name' => 'An-Nasr', 'translation' => 'The Divine Support', 'verses' => 3, 'revelation' => 'Medinan'],
            ['number' => 111, 'name' => 'Al-Masad', 'translation' => 'The Palm Fiber', 'verses' => 5, 'revelation' => 'Meccan'],
            ['number' => 112, 'name' => 'Al-Ikhlas', 'translation' => 'The Sincerity', 'verses' => 4, 'revelation' => 'Meccan'],
            ['number' => 113, 'name' => 'Al-Falaq', 'translation' => 'The Daybreak', 'verses' => 5, 'revelation' => 'Meccan'],
            ['number' => 114, 'name' => 'An-Nas', 'translation' => 'Mankind', 'verses' => 6, 'revelation' => 'Meccan'],
        ];
    }

    /**
     * Get information about a specific Surah
     */
    private function getSurahInfo($surahNumber)
    {
        $surahs = $this->getSurahList();
        
        foreach ($surahs as $surah) {
            if ($surah['number'] == $surahNumber) {
                return $surah;
            }
        }

        return null;
    }

    /**
     * Get maximum ayah count for a surah (All 114 Surahs)
     */
    private function getMaxAyahForSurah($surahNumber)
    {
        $ayahCounts = [
            1 => 7, 2 => 286, 3 => 200, 4 => 176, 5 => 120,
            6 => 165, 7 => 206, 8 => 75, 9 => 129, 10 => 109,
            11 => 123, 12 => 111, 13 => 43, 14 => 52, 15 => 99,
            16 => 128, 17 => 111, 18 => 110, 19 => 98, 20 => 135,
            21 => 112, 22 => 78, 23 => 118, 24 => 64, 25 => 77,
            26 => 227, 27 => 93, 28 => 88, 29 => 69, 30 => 60,
            31 => 34, 32 => 30, 33 => 73, 34 => 54, 35 => 45,
            36 => 83, 37 => 182, 38 => 88, 39 => 75, 40 => 85,
            41 => 54, 42 => 53, 43 => 89, 44 => 59, 45 => 37,
            46 => 35, 47 => 38, 48 => 29, 49 => 18, 50 => 45,
            51 => 60, 52 => 49, 53 => 62, 54 => 55, 55 => 78,
            56 => 96, 57 => 29, 58 => 22, 59 => 24, 60 => 13,
            61 => 14, 62 => 11, 63 => 11, 64 => 18, 65 => 12,
            66 => 12, 67 => 30, 68 => 52, 69 => 52, 70 => 44,
            71 => 28, 72 => 28, 73 => 20, 74 => 56, 75 => 40,
            76 => 31, 77 => 50, 78 => 40, 79 => 46, 80 => 42,
            81 => 29, 82 => 19, 83 => 36, 84 => 25, 85 => 22,
            86 => 17, 87 => 19, 88 => 26, 89 => 30, 90 => 20,
            91 => 15, 92 => 21, 93 => 11, 94 => 8, 95 => 8,
            96 => 19, 97 => 5, 98 => 8, 99 => 8, 100 => 11,
            101 => 11, 102 => 8, 103 => 3, 104 => 9, 105 => 5,
            106 => 4, 107 => 7, 108 => 3, 109 => 6, 110 => 3,
            111 => 5, 112 => 4, 113 => 5, 114 => 6
        ];

        return $ayahCounts[$surahNumber] ?? 7;
    }
}
