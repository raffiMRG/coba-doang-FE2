<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function index()
    {
        // Panggil API eksternal
        $response = $this->backend()->get('/update');

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
