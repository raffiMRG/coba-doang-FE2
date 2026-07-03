<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookmarkController extends Controller
{
  public function index(Request $request)
  {
    // Ambil halaman saat ini dari query parameter, default 1
    $page = $request->query('page', 1);

    // Ambil data dari API
    $response = $this->backend()->get('/bookmarks', [
      'page' => $page,
      'limit' => 20
    ]);

    if ($response->failed()) {
      Log::error('Failed to fetch /bookmarks from backend', [
        'status' => $response->status(),
        'body' => $response->body(),
      ]);

      return view('bookmark', [
        'folders' => [],
        'error' => config('app.debug')
          ? "Gagal mengambil data dari API (HTTP {$response->status()}): {$response->body()}"
          : 'Gagal mengambil data dari API.',
        'pagination' => null
      ]);
    }

    $data = $response->json();

    return view('bookmark', [
      'folders' => $data['Data']['items'] ?? [],
      'error' => null,
      'page' => $data['Data']['page'] ?? 1,
      'pages' => $data['Data']['pages'] ?? 1,
      'baseUrl' => url('/bookmark')
    ]);
  }
}
