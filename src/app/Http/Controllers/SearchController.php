<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->query('query');
    $page = $request->query('page', 1);
    $error = null;
    $folders = [];
    $pages = 0;
    $baseUrl = '/search?query=' . urlencode($query);
    $apiUrl = config('app.api_url');

    if (!$query) {
      $error = "Masukkan kata kunci pencarian.";
      return view('search', compact('error', 'folders', 'page', 'pages', 'baseUrl'));
    }

    try {
      $response = Http::get("{$apiUrl}/search", [
        // $response = Http::get("http://192.168.1.133:8181/search", [
        'q' => $query,
        'page' => $page,
      ]);

      if ($response->failed()) {
        $error = "Gagal mengambil data dari server.";
      } else {
        $json = $response->json();
        $folders = $json['data'] ?? [];
        $page = $json['page'] ?? 1;
        $total = $json['total_data'] ?? 0;
        $perPage = $json['per_page'] ?? 20;
        $pages = ceil($total / $perPage);
      }
    } catch (\Exception $e) {
      $error = "Terjadi kesalahan: " . $e->getMessage();
    }

    return view('search', compact('error', 'folders', 'page', 'pages', 'baseUrl'));
  }
}
