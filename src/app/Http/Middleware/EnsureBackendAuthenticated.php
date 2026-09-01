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
        if (session('access_token') && !$this->isExpiredOrExpiringSoon(session('access_token'))) {
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

    /**
     * Decodes a JWT's payload without verifying its signature — signature
     * verification is still enforced by the backend on every request; this
     * is only used to read the `exp` claim so we know to proactively refresh
     * before sending a token we already know is stale.
     */
    private function decodeJwtPayload(string $jwt): ?array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return null;
        }

        $payload = strtr($parts[1], '-_', '+/');
        $payload .= str_repeat('=', (4 - strlen($payload) % 4) % 4);

        $decoded = base64_decode($payload, true);

        return $decoded === false ? null : json_decode($decoded, true);
    }

    private function isExpiredOrExpiringSoon(string $jwt, int $bufferSeconds = 30): bool
    {
        $exp = $this->decodeJwtPayload($jwt)['exp'] ?? null;

        return !$exp || $exp <= (time() + $bufferSeconds);
    }
}
