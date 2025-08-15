<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UpdateController extends Controller
{
    public function index()
    {
        // Panggil API eksternal
        $response = Http::get('http://192.168.1.133:8080/update');

        if ($response->failed()) {
            return view('update', [
                'messages' => [],
                'error' => 'Gagal memuat data dari API.'
            ]);
        }

        $data = $response->json();
        $messages = $data['messages'] ?? [];

        return view('update', [
            'messages' => $messages,
            'error' => null
        ]);
    }
}
