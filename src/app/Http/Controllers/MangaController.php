<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class MangaController extends Controller
{
    public function show($id)
    {
        // Ambil data dari API
        $response = Http::get("http://192.168.1.133:8080/id/{$id}");

        if ($response->failed()) {
            abort(404, "Data manga tidak ditemukan.");
        }

        $data = $response->json();

        if (!isset($data['Data'])) {
            abort(500, "Format data tidak sesuai.");
        }

        return view('manga.show', [
            'manga' => $data['Data']
        ]);
    }
}
