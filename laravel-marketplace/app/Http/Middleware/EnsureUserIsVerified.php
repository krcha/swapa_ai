<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        if (!$user->isFullyVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Account verification required',
                'data' => [
                    'verification_status' => [
                        'email' => $user->is_email_verified,
                        'sms' => $user->is_sms_verified,
                        'age' => $user->is_age_verified,
                        'overall' => false
                    ],
                    'verification_required' => true
                ]
            ], 403);
        }

        return $next($request);
    }
}
