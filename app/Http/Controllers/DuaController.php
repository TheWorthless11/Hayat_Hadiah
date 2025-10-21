<?php

namespace App\Http\Controllers;

use App\Models\Dua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DuaController extends Controller
{
    // List duas by category/subsection, with search
    public function index(Request $request)
    {
        // If it has query params (AJAX request), return JSON
        if ($request->has('category') || $request->wantsJson() || $request->ajax()) {
            $category = $request->query('category', 'General');
            $subsection = $request->query('subsection', null);
            $q = $request->query('q', null);

            $query = Dua::query()->where('category', $category);

            if ($subsection) {
                $query->where('subsection', $subsection);
            }

            // Only show public duas or user's own
            if (Auth::check()) {
                $query->where(function($q2) {
                    $q2->where('is_public', true)->orWhere('user_id', Auth::id());
                });
            } else {
                $query->where('is_public', true);
            }

            if ($q) {
                $query->where(function($s) use ($q) {
                    $s->where('title', 'like', "%{$q}%")
                      ->orWhere('arabic_text', 'like', "%{$q}%")
                      ->orWhere('transliteration', 'like', "%{$q}%")
                      ->orWhere('translation', 'like', "%{$q}%");
                });
            }

            $duas = $query->orderBy('created_at', 'desc')->paginate(20);

            return response()->json(['success' => true, 'duas' => $duas]);
        }

        // Regular page visit - return Blade view
        return view('dua.index');
    }

    // Store a new dua
    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => 'required|string|max:191',
            'subsection' => 'nullable|string|max:191',
            'title' => 'nullable|string|max:255',
            'arabic_text' => 'nullable|string',
            'transliteration' => 'nullable|string',
            'translation' => 'nullable|string',
            'is_public' => 'sometimes|boolean',
        ]);

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
            // Admin can set public true; for now allow is_public as provided
        } else {
            // Not authenticated: mark as private to the creator, but since no user_id, keep is_public false and reject create? For now accept but is_public=false
            $data['is_public'] = false;
        }

        $dua = Dua::create($data);

        return response()->json(['success' => true, 'dua' => $dua]);
    }

    // Update dua
    public function update(Request $request, Dua $dua)
    {
        // Only owner or admin (future) can update
        if (!Auth::check() || (Auth::id() !== $dua->user_id)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'arabic_text' => 'nullable|string',
            'transliteration' => 'nullable|string',
            'translation' => 'nullable|string',
            'is_public' => 'sometimes|boolean',
        ]);

        $dua->update($data);

        return response()->json(['success' => true, 'dua' => $dua]);
    }

    // Delete dua
    public function destroy(Dua $dua)
    {
        if (!Auth::check() || (Auth::id() !== $dua->user_id)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $dua->delete();

        return response()->json(['success' => true]);
    }
}
