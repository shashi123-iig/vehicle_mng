<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyDriverToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $headerToken = $request->bearerToken();

        if (!$headerToken) {
            return response()->json([
                'status' => false,
                'message' => 'Token not provided'
            ], 401);
        }

        $decoded = base64_decode($headerToken);
        [$mobile, $timestamp] = explode('|', $decoded);

        if (empty($mobile)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        $request->merge(['driver_mobile' => $mobile]);

        return $next($request);
    }
}
