<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class Controller
{
    /**
     * Pre-authenticated HTTP client for the Go backend — attaches the
     * current session's access token as a Bearer header, since every
     * backend route (besides /login, /refresh, /health) requires one.
     */
    protected function backend(): PendingRequest
    {
        return Http::withToken(session('access_token'))
            ->baseUrl(rtrim(config('app.api_url'), '/'));
    }

    /**
     * Same-origin JSON relay to a local worker daemon (extract/translate) —
     * the daemon only binds on the laptop's LAN IP, unreachable from a phone
     * on the server's network, so the call has to happen server-side and
     * get relayed back. Short finite timeout: these are quick synchronous
     * calls, not the long-lived /progress stream (see proxyDaemonSse).
     */
    protected function proxyDaemonJson(string $daemonUrl, string $method, string $path, ?array $json = null): JsonResponse
    {
        try {
            $response = Http::baseUrl(rtrim($daemonUrl, '/'))
                ->timeout(10)
                ->send($method, $path, $json !== null ? ['json' => $json] : []);
        } catch (ConnectionException $e) {
            return response()->json(['error' => 'Worker daemon unreachable'], 502);
        }

        return response()->json($response->json() ?? [], $response->status());
    }

    /**
     * Same-origin SSE relay for a daemon's /progress stream — mirrors
     * FolderController::progress(), the existing precedent for this exact
     * problem. Finite 55s timeout (unlike FolderController's timeout(0)):
     * extract/translate pages open this connection unconditionally on page
     * load (not gated behind a Start click), so an idle tab with no job
     * running would otherwise pin a PHP-FPM worker forever. On timeout the
     * connection just closes; the blade JS's existing needsReconnect/
     * setInterval(...,5000) logic already reopens it automatically.
     */
    protected function proxyDaemonSse(string $daemonUrl, string $path): StreamedResponse
    {
        $upstream = Http::withOptions(['stream' => true])
            ->baseUrl(rtrim($daemonUrl, '/'))
            ->timeout(55)
            ->get($path);

        return response()->stream(function () use ($upstream) {
            if ($upstream->failed()) {
                return;
            }

            $body = $upstream->toPsrResponse()->getBody();
            while (!$body->eof()) {
                if (connection_aborted()) {
                    break;
                }
                echo $body->read(1024);
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
