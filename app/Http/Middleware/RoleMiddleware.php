<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Check if user's role matches the required one
        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
