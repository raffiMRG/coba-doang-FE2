<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UpdateController;
use Illuminate\Support\Facades\Route;

// Public — "/" is a decoy showing the stock Laravel welcome page (doesn't
// hint that a login/admin area exists at all). The real login lives at
// /login. Everything else requires an authenticated session.
Route::get('/', function () {
  return view('welcome');
});
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth.backend')->group(function () {
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

  Route::get('/home', [HomeController::class, 'index'])->name('home');
  Route::get('/bookmark', [BookmarkController::class, 'index'])->name('bookmark');
  Route::get('/status', [FolderController::class, 'index'])->name('status');
  Route::get('/search', [SearchController::class, 'index'])->name('search');
  Route::get('/id/{id}', [MangaController::class, 'show']);
  Route::get('/update', [UpdateController::class, 'index'])->name('update.index');

  Route::get('/backup', [BackupController::class, 'index'])->name('backup');
  Route::get('/backup/export', [BackupController::class, 'export'])->name('backup.export');
  Route::post('/backup/import', [BackupController::class, 'import'])->name('backup.import');
});
