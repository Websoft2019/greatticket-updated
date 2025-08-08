<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if the authenticated user has the required role
        if (auth()->check() && auth()->user()->role === $role) {
            return $next($request);
        }

        // Optionally, you can return a 403 unauthorized response or redirect the user
        return redirect('/'); // or return abort(403);
    }
}
