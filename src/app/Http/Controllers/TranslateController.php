<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TranslateController extends Controller
{
  public function index()
  {
    $response = $this->backend()->get('/translate/pending');

    if ($response->failed()) {
      Log::error('Failed to fetch /translate/pending from backend', [
        'status' => $response->status(),
        'body' => $response->body(),
      ]);

      return view('translate', [
        'items' => [],
        'error' => config('app.debug')
          ? "Gagal mengambil data dari API (HTTP {$response->status()}): {$response->body()}"
          : 'Gagal mengambil data dari API.'
      ]);
    }

    return view('translate', [
      'items' => $response->json()['Data'] ?? [],
      'error' => null,
    ]);
  }

  /**
   * Same-origin relay for the "Request Translate" button's browser fetch()
   * on manga/show.blade.php — same reason as BookmarkController::toggle().
   */
  public function request(string $id)
  {
    $response = $this->backend()->post("/translate/{$id}/request");

    return response()->json($response->json(), $response->status());
  }

  /**
   * The translate worker daemon only runs on the user's own laptop, which a
   * phone on the server's network can't reach directly — so this server
   * proxies every daemon call (translate.blade.php now calls this server,
   * same-origin, instead of the daemon's LAN address directly).
   */
  public function ping()
  {
    return $this->proxyDaemonJson(config('app.translate_daemon_url'), 'GET', '/ping');
  }

  public function start(Request $request)
  {
    return $this->proxyDaemonJson(
      config('app.translate_daemon_url'),
      'POST',
      '/start',
      ['folder_ids' => $request->input('folder_ids', [])]
    );
  }

  public function progress()
  {
    return $this->proxyDaemonSse(config('app.translate_daemon_url'), '/progress');
  }
}
