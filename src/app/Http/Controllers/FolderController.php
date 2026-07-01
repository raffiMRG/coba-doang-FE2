<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FolderController extends Controller
{
  public function index(Request $request)
  {
    // Ambil halaman saat ini dari query parameter, default 1
    $page = $request->query('page', 1);

    // Ambil data dari API
    $response = $this->backend()->get('/folders', ['page' => $page, 'limit' => 100]);

    if ($response->failed()) {
      return view('main', [
        'folders' => [],
        'error' => 'Gagal mengambil data dari API.'
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
