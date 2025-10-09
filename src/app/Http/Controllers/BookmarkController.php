<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookmarkController extends Controller
{
  public function index(Request $request)
  {
    // Ambil halaman saat ini dari query parameter, default 1
    $page = $request->query('page', 1);

    // Ambil data dari API
    $apiUrl = config('app.api_url');
    // $apiUrl = 'http://192.168.1.133:8181';
    $response = Http::get("{$apiUrl}/bookmarks?page=1&limit=20", [
      'page' => $page
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
