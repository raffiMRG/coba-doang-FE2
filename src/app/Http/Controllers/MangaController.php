<?php

namespace App\Http\Controllers;

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

    // Sort halaman manga (natural order, case insensitive)
    if (isset($data['Data']['page']) && is_array($data['Data']['page'])) {
      usort($data['Data']['page'], function ($a, $b) {
        return strnatcmp($a, $b); // natural sorting: 1,2,10,11,20
      });
    }

    // dd($data);

    return view('manga.show', [
      'manga' => $data['Data']
    ]);
  }
}
