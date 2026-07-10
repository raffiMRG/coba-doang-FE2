<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Re-serves the Go backend's public /new and /sementara static file routes
 * under the frontend's own origin. Thumbnail URLs stored in the backend DB
 * bake in a single fixed host (whatever API_BASEURL was at scan time), which
 * breaks as soon as the app is reachable from more than one place (LAN,
 * Tailscale, public HTTPS tunnel) — browsers block the mismatched scheme/host
 * as mixed content when the page itself is HTTPS. Proxying through the same
 * origin the page was loaded from sidesteps that entirely.
 */
class MediaController extends Controller
{
    public function newFile(string $path)
    {
        return $this->stream('new', $path);
    }

    public function sementaraFile(string $path)
    {
        return $this->stream('sementara', $path);
    }

    private function stream(string $dir, string $path): StreamedResponse
    {
        // Guard against path traversal by checking whole path segments, not
        // just "does the string contain '..' anywhere" — some real folder
        // names legitimately contain a literal "..." (e.g. a truncated
        // release title ending in an ellipsis), which a bare substring check
        // would wrongly reject even though no segment is actually "..".
        foreach (explode('/', $path) as $segment) {
            if ($segment === '..' || $segment === '.') {
                abort(400);
            }
        }

        // Http::get() runs the URL through Guzzle's RFC 6570 UriTemplate::expand()
        // unconditionally (see Illuminate\Http\Client\PendingRequest::buildUrl()).
        // Folder names containing literal `{`/`}` (e.g. a "{groupname}" scan-tag)
        // get misread as unresolved template placeholders and silently dropped,
        // so percent-encode them first to keep them opaque to that expansion.
        $escapedPath = str_replace(['{', '}'], ['%7B', '%7D'], $path);

        $upstream = Http::withOptions(['stream' => true])
            ->baseUrl(rtrim(config('app.api_url'), '/'))
            ->get("{$dir}/{$escapedPath}");

        if ($upstream->failed()) {
            abort($upstream->status());
        }

        $body = $upstream->toPsrResponse()->getBody();

        return new StreamedResponse(function () use ($body) {
            while (!$body->eof()) {
                echo $body->read(8192);
            }
        }, 200, [
            'Content-Type' => $upstream->header('Content-Type'),
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
