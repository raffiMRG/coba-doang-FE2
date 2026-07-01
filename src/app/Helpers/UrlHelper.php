<?php

// if (!function_exists('thumbnail_url')) {
//     /**
//      * Generate correct URL for thumbnail
//      *
//      * @param string $path Full path or relative path
//      * @return string
//      */
//     function thumbnail_url(string $path): string
//     {
//         // Jika path sudah diawali http:// atau https://, kembalikan begitu saja
//         if (preg_match('/^https?:\/\//', $path)) {
//             return $path;
//         }

//         // Ganti backslash ke slash
//         $path = str_replace('\\', '/', $path);

//         // URL encode karakter khusus
//         $encodedPath = implode('/', array_map('rawurlencode', explode('/', $path)));

//         // Prefix dengan domain server tempat file bisa diakses
//         $baseUrl = 'http://192.168.1.133:8080';

//         return $baseUrl . '/' . ltrim($encodedPath, '/');
//     }
// }


// =========================================================
// if (!function_exists('clean_thumbnail_url')) {
//     function clean_thumbnail_url($url) {
//         // Ganti http:/ menjadi http://
//         $url = preg_replace('#^http:/([^/])#', 'http://$1', $url);
//         // Encode karakter khusus
//         return preg_replace_callback('/[^A-Za-z0-9\-_.:\/]/', function($matches) {
//             return urlencode($matches[0]);
//         }, $url);
//     }
// }

// ===========================================================
// if (!function_exists('clean_thumbnail_url')) {
//     function clean_thumbnail_url($url) {
//         // pastikan ada double slash setelah http:
//         $url = preg_replace('#^http:/([^/])#', 'http://$1', $url);

//         // pisah path dan base
//         $parts = parse_url($url);
//         if (!isset($parts['path'])) return $url;

//         // encode spasi dan karakter ilegal tapi biarkan / (path separator) tetap
//         $path = implode('/', array_map('rawurlencode', explode('/', $parts['path'])));

//         // gabungkan kembali
//         $fixed = $parts['scheme'].'://'.$parts['host'] . $path;
//         if (isset($parts['query'])) $fixed .= '?'.$parts['query'];

//         return $fixed;
//     }
// }


// ========================================================
// if (!function_exists('fix_thumbnail_url')) {
//     /**
//      * Mengembalikan URL thumbnail yang aman untuk digunakan di browser
//      * 
//      * @param string $url
//      * @return string
//      */
//     function fix_thumbnail_url(string $url): string
//     {
//         // Pastikan http:/ menjadi http://
//         $url = preg_replace('#^http:/([^/])#', 'http://$1', $url);

//         $parts = parse_url($url);
//         if (!isset($parts['path'])) {
//             return $url;
//         }

//         // Pecah path per folder/file
//         $segments = explode('/', $parts['path']);
//         $segments = array_map('rawurlencode', $segments);

//         // Gabungkan kembali path
//         $path = implode('/', $segments);

//         $fixed = $parts['scheme'] . '://' . $parts['host'] . $path;

//         if (isset($parts['query'])) {
//             $fixed .= '?' . $parts['query'];
//         }

//         if (isset($parts['fragment'])) {
//             $fixed .= '#' . $parts['fragment'];
//         }

//         return $fixed;
//     }
// }
