<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
      return view('bookmark', [
        'folders' => [],
        'error' => 'Gagal mengambil data dari API.',
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
