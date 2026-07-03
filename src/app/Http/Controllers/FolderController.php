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
   * Same-origin relay for the SSE progress stream in main.blade.php.
   * EventSource can't send an Authorization header at all, so the browser
   * can't hit the backend's protected /folders/progress/:taskID directly —
   * stream it through here instead, forwarding chunks as they arrive.
   */
  public function progress(string $taskId)
  {
    $upstream = $this->backend()
      ->withOptions(['stream' => true])
      ->timeout(0)
      ->get("/folders/progress/{$taskId}");

    return response()->stream(function () use ($upstream) {
      $body = $upstream->toPsrResponse()->getBody();
      while (!$body->eof()) {
        echo $body->read(1024);
        if (ob_get_level() > 0) {
          ob_flush();
        }
        flush();
      }
    }, 200, [
      'Content-Type' => 'text/event-stream',
      'Cache-Control' => 'no-cache',
      'X-Accel-Buffering' => 'no',
      'Connection' => 'keep-alive',
    ]);
  }
}
