<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the authenticated user has the role 'a'
        if (Auth::check() && Auth::user()->role === 'a') {
            return $next($request);
        }

        // If not, redirect to a different page or show a 403 Forbidden error
        return redirect('/dashboard')->with('error', 'You do not have access to this page.');
    }
}
