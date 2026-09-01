<?php

namespace App\Http\Controllers;

class ExtractController extends Controller
{
    /**
     * The worker daemon only runs on the user's own laptop, which a phone
     * on the server's network can't reach directly — so this server proxies
     * every daemon call (see ping/scan/start/progress below and
     * extract.blade.php, which now calls this server, same-origin, instead
     * of the daemon's LAN address directly).
     */
    public function index()
    {
        return view('extract');
    }

    public function ping()
    {
        return $this->proxyDaemonJson(config('app.extract_daemon_url'), 'GET', '/ping');
    }

    public function scan()
    {
        return $this->proxyDaemonJson(config('app.extract_daemon_url'), 'GET', '/scan');
    }

    public function start()
    {
        return $this->proxyDaemonJson(config('app.extract_daemon_url'), 'POST', '/start');
    }

    public function progress()
    {
        return $this->proxyDaemonSse(config('app.extract_daemon_url'), '/progress');
    }
}
