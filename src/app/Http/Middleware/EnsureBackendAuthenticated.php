<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class EnsureBackendAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session('access_token')) {
            return $next($request);
        }

        $refreshToken = session('refresh_token');
        if ($refreshToken) {
            $apiUrl = config('app.api_url');
            $response = Http::post("{$apiUrl}/refresh", ['refresh_token' => $refreshToken]);

            if ($response->successful()) {
                session(['access_token' => $response->json('Data.access_token')]);
                return $next($request);
            }
        }

        session()->forget(['access_token', 'refresh_token']);

        return redirect('/');
    }
}
