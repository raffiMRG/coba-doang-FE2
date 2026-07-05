<?php

if (!function_exists('same_origin_url')) {
    /**
     * Strips the scheme+host the backend baked into a stored thumbnail/page
     * URL (from whatever API_BASEURL was at scan time), keeping only the
     * path (already percent-encoded), so <img> tags resolve against
     * MediaController's same-origin /new and /sementara routes instead of a
     * fixed host that may not match how the page itself was reached.
     */
    function same_origin_url(?string $url): ?string
    {
        if (!$url) {
            return $url;
        }

        $path = parse_url($url, PHP_URL_PATH);

        return $path ?: $url;
    }
}

if (!function_exists('thumbnail_url')) {
    /**
     * Outside production (local dev, where SRC_DIR/DST_DIR usually aren't
     * the real mounted data), always return a fixed placeholder instead of
     * a same-origin URL that would 404. In production, resolve the real
     * thumbnail via same_origin_url() — falling back to the placeholder too
     * if $url is empty (some catalog entries bulk-imported from an old
     * backup have no thumbnail at all).
     */
    function thumbnail_url(?string $url): string
    {
        $placeholder = 'https://i.imgur.com/TNOs1Xx.png';

        if (!app()->environment('production') || !$url) {
            return $placeholder;
        }

        return same_origin_url($url) ?? $placeholder;
    }
}
