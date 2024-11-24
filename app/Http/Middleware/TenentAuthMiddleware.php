<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenentAuthMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        // Check if the user is authenticated
        if (!Auth::check()) {
            // Redirect to the login page if not authenticated
            return redirect()->route('tenant.users.login');
        }

        return $next($request);
    }
}
