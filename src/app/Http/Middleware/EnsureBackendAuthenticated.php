<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class EnsureBackendAuthenticated
{
    private const REMEMBER_COOKIE = 'remember_refresh_token';

    public function handle(Request $request, Closure $next): Response
    {
        if (session('access_token')) {
            return $next($request);
        }

        // Session refresh token first, falling back to the long-lived
        // "remember me" cookie if the session itself expired/was cleared.
        $refreshToken = session('refresh_token') ?: $request->cookie(self::REMEMBER_COOKIE);

        if ($refreshToken) {
            $apiUrl = config('app.api_url');
            $response = Http::post("{$apiUrl}/refresh", ['refresh_token' => $refreshToken]);

            if ($response->successful()) {
                session([
                    'access_token' => $response->json('Data.access_token'),
                    'refresh_token' => $refreshToken,
                ]);
                return $next($request);
            }
        }

        session()->forget(['access_token', 'refresh_token']);

        return redirect('/login');
    }
}
