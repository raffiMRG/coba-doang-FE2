<?php

namespace App\Http\Controllers;

class ExtractController extends Controller
{
    /**
     * Everything on this page talks directly from the browser to the local
     * worker daemon (see extract.blade.php) — the daemon only runs on the
     * user's own laptop, which this server has no network path to, so
     * there's nothing to fetch from the Go backend here.
     */
    public function index()
    {
        return view('extract');
    }
}
