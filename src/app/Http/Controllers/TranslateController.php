<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

  public function settings()
  {
    $response = $this->backend()->get('/translate/settings');

    return view('translate.settings', [
      'settings' => $response->successful() ? $response->json('Data') : null,
      'error' => $response->successful() ? null : 'Gagal mengambil setting dari API.',
    ]);
  }

  public function saveSettings(Request $request)
  {
    $response = $this->backend()->put('/translate/settings', [
      'translator' => $request->input('translator'),
      'target_lang' => $request->input('target_lang'),
      'detector' => $request->input('detector'),
      'ocr' => $request->input('ocr'),
      'inpainter' => $request->input('inpainter'),
      'detection_size' => (int) $request->input('detection_size'),
      'inpainting_size' => (int) $request->input('inpainting_size'),
      'inpainting_precision' => $request->input('inpainting_precision'),
      'gpu_mode' => $request->input('gpu_mode'),
      'api_base' => (string) $request->input('api_base'),
      'api_model' => (string) $request->input('api_model'),
      'api_key' => (string) $request->input('api_key'),
      'clear_api_key' => $request->boolean('clear_api_key'),
    ]);

    if ($response->failed()) {
      // Never flash the API key back into the session.
      return back()->withInput($request->except('api_key'))
        ->with('error', $response->json('Message') ?? 'Gagal menyimpan setting.');
    }

    return redirect()->route('translate.settings')->with('success', 'Setting tersimpan. Berlaku mulai batch berikutnya.');
  }

  public function resetSettings()
  {
    $response = $this->backend()->delete('/translate/settings');

    return redirect()->route('translate.settings')->with(
      $response->successful() ? 'success' : 'error',
      $response->successful() ? 'Setting dikembalikan ke default.' : 'Gagal reset setting.'
    );
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
   * Same-origin relay for the "keluarkan dari antrian" button on the
   * translate queue page — same reason as request() above.
   */
  public function cancel(string $id)
  {
    $response = $this->backend()->delete("/translate/{$id}");

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

  public function stop()
  {
    return $this->proxyDaemonJson(config('app.translate_daemon_url'), 'POST', '/stop');
  }

  public function progress()
  {
    return $this->proxyDaemonSse(config('app.translate_daemon_url'), '/progress');
  }

  /**
   * Live tail of the currently-processing folder's subprocess output —
   * same relay pattern as progress(), used by history-show.blade.php while
   * a job is still in flight.
   */
  public function log()
  {
    return $this->proxyDaemonSse(config('app.translate_daemon_url'), '/log');
  }

  /**
   * Server-rendered (not client-JS proxyDaemonJson) since this is a normal
   * page load, not a fetch() call from translate.blade.php's inline script —
   * same pattern as index() fetching from the Go backend.
   */
  public function history()
  {
    $response = Http::baseUrl(rtrim(config('app.translate_daemon_url'), '/'))->timeout(10)->get('/history');

    return view('translate.history', [
      'jobs' => $response->successful() ? ($response->json('jobs') ?? []) : [],
      'error' => $response->successful() ? null : 'Gagal mengambil riwayat dari worker.',
    ]);
  }

  public function historyShow($jobId)
  {
    $response = Http::baseUrl(rtrim(config('app.translate_daemon_url'), '/'))->timeout(10)->get("/history/{$jobId}");

    if ($response->status() === 404) {
      abort(404, 'Riwayat job tidak ditemukan.');
    }

    $data = $response->json();

    return view('translate.history-show', [
      'job' => $data['job'] ?? null,
      'items' => $data['items'] ?? [],
    ]);
  }
}
