<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\UpdateController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/folders', [FolderController::class, 'index']);

// Halaman utama
// Route::get('/home', function () {
//     return view('home');
// })->name('home');

Route::get('/home', [HomeController::class, 'index'])->name('home');
// Route::get('/home', [HomeController::class, 'index'])->name('home');

// Halaman main
// Route::get('/main', function () {
//     return view('main');
// })->name('main');

// Halaman main
Route::get('/status', [FolderController::class, 'index'])->name('status');


Route::get('/id/{id}', [MangaController::class, 'show']);

Route::get('/update', [UpdateController::class, 'index'])->name('update.index');