<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BugReportController extends Controller
{
  public function index(Request $request)
  {
    $status = $request->query('status', 'all');
    $sort = $request->query('sort', 'newest');

    $response = $this->backend()->get('/bug-reports', [
      'status' => $status,
      'sort' => $sort,
    ]);

    return view('bug-reports.index', [
      'items' => $response->successful() ? ($response->json()['Data'] ?? []) : [],
      'error' => $response->successful() ? null : 'Gagal mengambil data laporan bug.',
      'status' => $status,
      'sort' => $sort,
    ]);
  }

  /**
   * Same-origin relay for the "Report Bug" modal's browser fetch() on
   * manga/show.blade.php — same reason as BookmarkController::toggle().
   */
  public function store(Request $request)
  {
    $response = $this->backend()->post('/bug-reports', [
      'folder_id' => $request->input('folder_id'),
      'description' => $request->input('description'),
    ]);

    return response()->json($response->json(), $response->status());
  }

  /**
   * Same-origin relay for the status toggle button on the bug reports list
   * page — same reason as store() above.
   */
  public function updateStatus(Request $request, string $id)
  {
    $response = $this->backend()->patch("/bug-reports/{$id}/status", [
      'status' => $request->input('status'),
    ]);

    return response()->json($response->json(), $response->status());
  }
}
