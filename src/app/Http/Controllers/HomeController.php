<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
Use Illuminate\Support\Facades\Http;

// class HomeController extends Controller
// {
//     public function index()
//     {
//         // Ambil data dari API
//         $response = Http::get('http://192.168.1.133:8080/newFolders');

//         if ($response->failed()) {
//             // return view('folders.index', [
//             return view('home', [
//                 'folders' => [],
//                 'error' => 'Gagal mengambil data dari API.'
//             ]);
//         }

//         $data = $response->json();

//         // return view('folders.index', [
//         return view('home', [
//             'folders' => $data['Data']['items'] ?? [],
//             'error' => null
//         ]);
//     }
// }


// =================================================
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
// ==========================================
class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil halaman saat ini dari query parameter, default 1
        $page = $request->query('page', 1);

        // Ambil data dari API
        $apiUrl = config('app.api_url');
        $response = Http::get("{$apiUrl}/newFolders", [
            'page' => $page
        ]);

        if ($response->failed()) {
            return view('home', [
                'folders' => [],
                'error' => 'Gagal mengambil data dari API.',
                'pagination' => null
            ]);
        }

        $data = $response->json();

        // return view('home', [
        //     'folders' => $data['Data']['items'] ?? [],
        //     'error' => null,
        //     'pagination' => [
        //         'page' => $data['Data']['page'] ?? 1,
        //         'pages' => $data['Data']['pages'] ?? 1,
        //         'limit' => $data['Data']['limit'] ?? 10,
        //         'total' => $data['Data']['total'] ?? 0,
        //     ]
        // ]);
        return view('home', [
          'folders' => $data['Data']['items'] ?? [],
          'error' => null,
          'page' => $data['Data']['page'] ?? 1,
          'pages' => $data['Data']['pages'] ?? 1,
          'baseUrl' => url('/home')
        ]);
    }
}