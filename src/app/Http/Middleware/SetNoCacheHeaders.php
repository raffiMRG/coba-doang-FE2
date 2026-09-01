<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Every page here is rendered server-side from live backend data —
 * manga name/thumbnail/status can change at any moment via rename/delete/
 * approve — and there is no client-side revalidation (no SPA, no service
 * worker). Without an explicit Cache-Control, mobile browsers (heuristic
 * caching + aggressive back-forward cache) were storing these responses and
 * serving stale listings on ordinary refresh/back navigation until the user
 * did a hard refresh. `no-store` specifically (not just `no-cache`) is what
 * opts a page out of bfcache in most browsers.
 *
 * Skips responses whose Content-Type is an image or an SSE stream — that's
 * MediaController's intentional `public, max-age=86400` on re-served images,
 * and the SSE endpoints' own `no-cache`, both left untouched without having
 * to enumerate routes.
 *
 * Deliberately NOT a "set only if the response doesn't already have a
 * Cache-Control" check: Symfony's ResponseHeaderBag constructor always
 * pre-populates Cache-Control with an empty string on every Response object
 * (see ResponseHeaderBag::__construct), so `$response->headers->has(...)` is
 * true even when nothing meaningful was ever set — that check silently
 * never fires, and Symfony's own conservative default
 * (`no-cache, private`, from computeCacheControlValue()) wins instead.
 */
class SetNoCacheHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $contentType = $response->headers->get('Content-Type', '');
        $isMediaOrStream = str_starts_with($contentType, 'image/')
            || str_starts_with($contentType, 'text/event-stream');

        if (!$isMediaOrStream) {
            $response->headers->set(
                'Cache-Control',
                'no-store, no-cache, must-revalidate, max-age=0'
            );
            $response->headers->set('Pragma', 'no-cache');
        }

        return $response;
    }
}
