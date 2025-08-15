<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class FolderController extends Controller
{
    public function index()
    {
        // Ambil data dari API
        $apiUrl = config('app.api_url');
        $response = Http::get("{$apiUrl}/folders");

        if ($response->failed()) {
            // return view('folders.index', [
            return view('main', [
                'folders' => [],
                'error' => 'Gagal mengambil data dari API.'
            ]);
        }

        $data = $response->json();

        // return view('folders.index', [
        return view('main', [
            'folders' => $data['Data'] ?? [],
            'error' => null
        ]);
    }
}
