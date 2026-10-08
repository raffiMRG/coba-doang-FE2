<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MangaController extends Controller
{
  public function show($id)
  {
    // Ambil data dari API
    $response = $this->backend()->get("/id/{$id}");
    // dd($response);

    if ($response->failed()) {
      // dd("Masuk kondiisi failed!!");
      Log::error("Failed to fetch /id/{$id} from backend", [
        'status' => $response->status(),
        'body' => $response->body(),
      ]);

      abort(404, config('app.debug')
        ? "Data manga tidak ditemukan (HTTP {$response->status()}): {$response->body()}"
        : "Data manga tidak ditemukan.");
    }

    $data = $response->json();

    if (!isset($data['Data'])) {
      abort(500, "Format data tidak sesuai.");
    }

    // Pages arrive already in reader order from the backend (PageFiles in
    // FolderRepositorys/Pages.go) — the same order the thumbnail is picked
    // from — so don't re-sort here, or page 1 and the thumbnail can drift.

    // dd($data);

    return view('manga.show', [
      'manga' => $data['Data']
    ]);
  }

  /**
   * Same-origin relay for the Edit modal's fetch() in manga/show.blade.php —
   * same reason as BookmarkController::toggle().
   */
  public function update(Request $request, $id)
  {
    $response = $this->backend()->patch("/id/{$id}", $request->all());

    return response()->json($response->json(), $response->status());
  }

  /**
   * Same-origin relay for the "Perbaiki thumbnail" button's fetch() in
   * manga/show.blade.php — recomputes the stored thumbnail URL from disk.
   */
  public function repairThumbnail($id)
  {
    $response = $this->backend()->post("/id/{$id}/thumbnail");

    return response()->json($response->json(), $response->status());
  }

  /**
   * Same-origin relay for the Delete modal's fetch() in manga/show.blade.php.
   */
  public function destroy(Request $request, $id)
  {
    $response = $this->backend()->delete("/id/{$id}", $request->all());

    return response()->json($response->json(), $response->status());
  }
}
