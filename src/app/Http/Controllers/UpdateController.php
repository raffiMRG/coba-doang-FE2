<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function index()
    {
        // Panggil API eksternal — timeout dinaikkan karena backend melakukan
        // 1 query DB per folder di SRC_DIR (cek exists + insert), bukan cuma
        // scan filesystem; bisa jauh lebih lambat dari default 30s Laravel
        // kalau folder yang belum di-scan lumayan banyak.
        $response = $this->backend()->timeout(120)->get('/update');

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
