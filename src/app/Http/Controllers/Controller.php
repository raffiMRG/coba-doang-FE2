<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

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
}
