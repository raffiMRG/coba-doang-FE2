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
