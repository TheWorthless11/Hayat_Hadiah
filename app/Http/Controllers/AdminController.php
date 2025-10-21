<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    // Return a JSON list of users (basic info) for the admin dashboard
    public function usersList(Request $request)
    {
        // Only admins should reach this route (middleware enforces it), but double-check
        if (!auth()->check() || !auth()->user()->is_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $users = User::orderBy('created_at', 'desc')
            ->get(['id', 'name', 'email', 'is_admin', 'created_at']);

        return response()->json(['success' => true, 'users' => $users]);
    }
}
