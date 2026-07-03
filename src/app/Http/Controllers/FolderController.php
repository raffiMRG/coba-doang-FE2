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
}
