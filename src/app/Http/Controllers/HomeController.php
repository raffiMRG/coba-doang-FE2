<?php

namespace App\Http\Controllers;
Use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil data dari API
        $response = Http::get('http://192.168.1.133:8080/newFolders');

        if ($response->failed()) {
            // return view('folders.index', [
            return view('home', [
                'folders' => [],
                'error' => 'Gagal mengambil data dari API.'
            ]);
        }

        $data = $response->json();

        // return view('folders.index', [
        return view('home', [
            'folders' => $data['Data']['items'] ?? [],
            'error' => null
        ]);
    }
}

// {
//     public function index()
//     {
//         $response = Http::get('http://192.168.1.133:8080/folders');
//         if($response->failed()) {
//             return view('home', [
//                 'folders' => [],
//                 'error' => 'Gagal mengambil data dari API.'
//             ]);
//         }

//         $data = $response->json();

//         return view('home', [
//             'folders' => $data['folders'] ?? [],
//             'error' => null
//         ]);
//     }
// }