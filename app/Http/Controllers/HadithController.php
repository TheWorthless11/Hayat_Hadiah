<?php

namespace App\Http\Controllers;

use App\Models\Hadith;
use Illuminate\Http\Request;

class HadithController extends Controller
{
    /**
     * Display the Hadith reader interface
     */
    public function index()
    {
        // Get list of Hadith collections
        $collections = $this->getHadithCollections();
        
        return view('hadith.index', compact('collections'));
    }

    /**
     * Get hadiths from a specific collection
     */
    public function getCollection(Request $request, $collection)
    {
        $request->validate([
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:50'
        ]);

        $perPage = $request->input('per_page', 10);

        $hadiths = Hadith::where('collection', $collection)
            ->orderBy('book')
            ->orderBy('reference')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'collection' => $collection,
            'hadiths' => $hadiths->items(),
            'pagination' => [
                'current_page' => $hadiths->currentPage(),
                'total_pages' => $hadiths->lastPage(),
                'per_page' => $hadiths->perPage(),
                'total' => $hadiths->total()
            ]
        ]);
    }

    /**
     * Get a specific hadith by ID
     */
    public function getHadith($id)
    {
        $hadith = Hadith::find($id);

        if (!$hadith) {
            return response()->json([
                'success' => false,
                'message' => 'Hadith not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'hadith' => $hadith
        ]);
    }

    /**
     * Search hadiths by keyword
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3',
            'collection' => 'nullable|string'
        ]);

        $query = $request->input('query');
        $collection = $request->input('collection');

        $hadiths = Hadith::where(function($q) use ($query) {
                $q->where('text', 'LIKE', "%{$query}%")
                  ->orWhere('translation', 'LIKE', "%{$query}%")
                  ->orWhere('narrator', 'LIKE', "%{$query}%");
            })
            ->when($collection, function($q) use ($collection) {
                return $q->where('collection', $collection);
            })
            ->orderBy('collection')
            ->orderBy('reference')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'results' => $hadiths,
            'count' => $hadiths->count()
        ]);
    }

    /**
     * Get hadith of the day
     */
    public function hadithOfTheDay()
    {
        // Generate a consistent random hadith for today
        $seed = date('Ymd');
        mt_srand($seed);
        
        $totalHadiths = Hadith::count();
        
        if ($totalHadiths === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No hadiths available'
            ], 404);
        }

        $randomIndex = mt_rand(0, $totalHadiths - 1);
        
        $hadith = Hadith::skip($randomIndex)->first();

        return response()->json([
            'success' => true,
            'hadith' => $hadith
        ]);
    }

    /**
     * Get random hadith
     */
    public function random()
    {
        $hadith = Hadith::inRandomOrder()->first();

        if (!$hadith) {
            return response()->json([
                'success' => false,
                'message' => 'No hadiths available'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'hadith' => $hadith
        ]);
    }

    /**
     * Get list of Hadith collections
     */
    private function getHadithCollections()
    {
        return [
            [
                'name' => 'Sahih Bukhari',
                'slug' => 'bukhari',
                'description' => 'The most authentic collection of Hadith',
                'books' => 97,
                'hadiths' => 7563
            ],
            [
                'name' => 'Sahih Muslim',
                'slug' => 'muslim',
                'description' => 'Second most authentic collection',
                'books' => 56,
                'hadiths' => 7190
            ],
            [
                'name' => 'Sunan Abu Dawood',
                'slug' => 'abudawud',
                'description' => 'Collection focused on legal matters',
                'books' => 43,
                'hadiths' => 5274
            ],
            [
                'name' => 'Jami\' at-Tirmidhi',
                'slug' => 'tirmidhi',
                'description' => 'Compilation with grading of authenticity',
                'books' => 46,
                'hadiths' => 3956
            ],
            [
                'name' => 'Sunan an-Nasa\'i',
                'slug' => 'nasai',
                'description' => 'Focus on Fiqh and worship',
                'books' => 51,
                'hadiths' => 5758
            ],
            [
                'name' => 'Sunan Ibn Majah',
                'slug' => 'ibnmajah',
                'description' => 'Contains unique hadiths not in other collections',
                'books' => 37,
                'hadiths' => 4341
            ],
            [
                'name' => 'Muwatta Malik',
                'slug' => 'malik',
                'description' => 'Earliest collection with legal rulings',
                'books' => 61,
                'hadiths' => 1594
            ],
            [
                'name' => 'Riyadh as-Salihin',
                'slug' => 'riyadussalihin',
                'description' => 'Collection focused on righteous deeds',
                'books' => 19,
                'hadiths' => 1896
            ],
            [
                'name' => '40 Hadith Nawawi',
                'slug' => 'nawawi',
                'description' => 'Forty essential hadiths compiled by Imam Nawawi',
                'books' => 1,
                'hadiths' => 42
            ]
        ];
    }
}
