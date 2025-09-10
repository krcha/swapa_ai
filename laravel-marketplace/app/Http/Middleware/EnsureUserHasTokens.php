<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasTokens
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

        if ($user->token_balance <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient tokens. Please purchase more tokens to continue.',
                'data' => [
                    'token_balance' => $user->token_balance,
                    'tokens_required' => true
                ]
            ], 403);
        }

        return $next($request);
    }
}
