<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'error' => true,
                    'message' => 'Unauthorized: User not found'
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized: Invalid or expired token',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}