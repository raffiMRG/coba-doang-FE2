<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FolderController extends Controller
{
  public function index(Request $request)
  {
    // Ambil halaman saat ini dari query parameter, default 1
    $page = $request->query('page', 1);

    // Ambil data dari API
    $response = $this->backend()->get('/folders', ['page' => $page, 'limit' => 100]);

    if ($response->failed()) {
      Log::error('Failed to fetch /folders from backend', [
        'status' => $response->status(),
        'body' => $response->body(),
      ]);

      return view('main', [
        'folders' => [],
        'error' => config('app.debug')
          ? "Gagal mengambil data dari API (HTTP {$response->status()}): {$response->body()}"
          : 'Gagal mengambil data dari API.'
      ]);
    }

    $data = $response->json();

    return view('main', [
      'folders' => $data['Data']['items'] ?? [],
      'error' => null,
      'page' => $data['Data']['pagination']['page'] ?? 1,
      'pages' => $data['Data']['pagination']['pages'] ?? 1,
      'baseUrl' => url('/status')
    ]);
  }

  /**
   * Same-origin relay for the "start move" browser fetch() in main.blade.php.
   * That JS runs client-side and never sees session('access_token'), so it
   * can't call the backend's now-protected POST /folders directly.
   */
  public function move(Request $request)
  {
    $response = $this->backend()->post('/folders', $request->all());

    return response()->json($response->json(), $response->status());
  }

  /**
   * nginx auth_request target for the global /status/events SSE stream.
   * Reaching here means auth.backend accepted the session (refreshing the
   * access token if needed); nginx copies X-Backend-Token into the
   * Authorization header of its proxied request to the Go backend and never
   * forwards it to the browser. The stream itself bypasses PHP entirely, so
   * an open /status tab doesn't hold a PHP-FPM worker.
   */
  public function sseAuth()
  {
    return response()->noContent()->header('X-Backend-Token', session('access_token'));
  }
}
