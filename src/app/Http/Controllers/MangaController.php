<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class MangaController extends Controller
{
    public function show($id)
    {
        // Ambil data dari API
        $apiUrl = config('app.api_url');
        $response = Http::get("{$apiUrl}/id/{$id}");

        if ($response->failed()) {
            abort(404, "Data manga tidak ditemukan.");
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

        return view('manga.show', [
            'manga' => $data['Data']
        ]);
    }
}
