<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and is an admin
        if (Auth::check() && Auth::user()->is_admin == 1) {
            return $next($request);
        }

        // Redirect non-admin users to the login page with an error message
        return redirect('/login')->withErrors([
            'username' => 'You do not have permission to access this resource.',
        ]);
    }
}
