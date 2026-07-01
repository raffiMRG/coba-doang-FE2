<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackupController extends Controller
{
  public function index()
  {
    return view('backup');
  }

  public function export(Request $request)
  {
    $mode = $request->query('mode', 'full');
    $response = $this->backend()->get('/export', ['mode' => $mode]);

    if ($response->failed()) {
      abort(500, 'Gagal export database dari backend.');
    }

    $filename = "manga-backup-{$mode}-" . now()->format('Ymd-His') . '.sql';

    return response($response->body(), 200, [
      'Content-Type' => 'application/sql',
      'Content-Disposition' => "attachment; filename={$filename}",
    ]);
  }

  public function import(Request $request)
  {
    $request->validate([
      'file' => 'required|file',
    ]);

    $uploaded = $request->file('file');

    $response = $this->backend()
      ->attach('file', file_get_contents($uploaded->getRealPath()), $uploaded->getClientOriginalName())
      ->post('/import');

    if ($response->failed()) {
      return back()->with('error', 'Gagal import database: ' . ($response->json('Message') ?? $response->body()));
    }

    return back()->with('success', 'Database berhasil di-import.');
  }
}
