<?php

namespace App\Http\Controllers;

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
}
