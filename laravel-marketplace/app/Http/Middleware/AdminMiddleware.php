<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access the admin panel.');
        }

        // Check if user is admin (you can customize this logic)
        $user = Auth::user();
        
        // For now, we'll use email-based admin check
        // You can change this to use a role system or any other method
        $adminEmails = [
            'admin@marketplace.com', // Add your email here
            'milan@example.com', // Add your actual email
            'admin@laravel-marketplace.com', // Marketplace admin email
        ];

        if (!in_array($user->email, $adminEmails)) {
            abort(403, 'Access denied. You are not authorized to access the admin panel.');
        }

        return $next($request);
    }
}