<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DuplicateController extends Controller
{
  public function index()
  {
    $response = $this->backend()->get('/duplicates', ['limit' => 100]);

    if ($response->failed()) {
      Log::error('Failed to fetch /duplicates from backend', [
        'status' => $response->status(),
        'body' => $response->body(),
      ]);

      return view('duplicates.index', [
        'items' => [],
        'error' => config('app.debug')
          ? "Gagal mengambil data dari API (HTTP {$response->status()}): {$response->body()}"
          : 'Gagal mengambil data dari API.'
      ]);
    }

    return view('duplicates.index', [
      'items' => $response->json('data') ?? [],
      'error' => null,
    ]);
  }

  public function show(string $id)
  {
    // Longer timeout than the default 30s: /compare hashes every matching
    // page's content to compute the diff (added/removed/changed/unchanged),
    // which on slow/network storage can take longer than Guzzle's default
    // for candidates with many matching pages.
    $response = $this->backend()->timeout(90)->get("/duplicates/{$id}/compare");

    if ($response->status() === 404) {
      abort(404, 'Kandidat duplikat tidak ditemukan.');
    }

    if ($response->failed()) {
      Log::error('Failed to fetch /duplicates/{id}/compare from backend', [
        'status' => $response->status(),
        'body' => $response->body(),
      ]);

      return redirect()->route('duplicates')->with('error', 'Gagal mengambil data perbandingan dari API.');
    }

    return view('duplicates.show', [
      'candidate' => $response->json('Data'),
    ]);
  }

  /**
   * Same-origin relay for the resolve buttons (buat judul baru / merge) on
   * duplicates/show.blade.php — same reason as BookmarkController::toggle().
   */
  public function resolve(Request $request, string $id)
  {
    $response = $this->backend()->post("/duplicates/{$id}/resolve", $request->only(['action', 'new_title', 'merge_mode']));

    return response()->json($response->json(), $response->status());
  }

  /**
   * Same-origin relay for the "Cek duplikat existing" button on
   * duplicates/index.blade.php.
   */
  public function backfill()
  {
    $response = $this->backend()->post('/duplicates/backfill');

    return response()->json($response->json(), $response->status());
  }
}
