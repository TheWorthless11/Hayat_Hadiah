<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in AND if their 'is_admin' column is true.
        if (!auth()->check() || !auth()->user()->is_admin) {
            // If not, show a 403 Forbidden error
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
