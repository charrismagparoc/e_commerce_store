<?php
// filepath: c:\laravel_web\shop-wave\app\Http\Middleware\RedirectGuestToAuthOptions.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectGuestToAuthOptions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            // Redirect unauthenticated users to the auth options page
            return redirect()->route('auth.options')->with('info', 'Please log in or register to continue.');
        }

        return $next($request);
    }
}