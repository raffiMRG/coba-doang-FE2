@extends('layouts.app')

@section('title', 'Riwayat Translate #' . ($job['id'] ?? ''))

@section('content')
  <div class="max-w-screen-xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold text-white tracking-tight">Job #{{ $job['id'] ?? '?' }}</h1>
      <a href="{{ route('translate.history') }}" class="text-sm text-indigo-400 hover:underline">&larr; Riwayat</a>
    </div>

    @if ($job)
      <div class="mb-6 p-5 bg-gray-900 rounded-xl ring-1 ring-white/10 grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
        <div>
          <p class="text-gray-500">Mulai</p>
          <p class="text-white">{{ $job['started_at'] }}</p>
        </div>
        <div>
          <p class="text-gray-500">Selesai</p>
          <p class="text-white">{{ $job['finished_at'] ?? 'Sedang berjalan...' }}</p>
        </div>
        <div>
          <p class="text-gray-500">Sukses</p>
          <p class="text-green-400 font-semibold">{{ $job['success_count'] }} / {{ $job['total'] }}</p>
        </div>
        <div>
          <p class="text-gray-500">Gagal</p>
          <p class="{{ $job['failed_count'] > 0 ? 'text-red-400 font-semibold' : 'text-gray-500' }}">{{ $job['failed_count'] }} / {{ $job['total'] }}</p>
        </div>
      </div>
    @endif

    @if (count($items) === 0)
      <p class="text-gray-500 text-center py-16">Tidak ada detail folder untuk job ini.</p>
    @else
      <div class="space-y-3">
        @foreach ($items as $item)
          <details data-folder-id="{{ $item['folder_id'] }}" {{ $item['status'] === 'processing' ? 'open' : '' }}
            class="bg-gray-900 rounded-xl ring-1 ring-white/10 overflow-hidden group">
            <summary class="px-5 py-4 flex items-center justify-between cursor-pointer list-none">
              <div class="min-w-0">
                <p class="text-white font-medium truncate">{{ $item['folder_name'] ?? "folder #{$item['folder_id']}" }}</p>
                <p class="item-message text-xs text-gray-500 mt-0.5">{{ $item['message'] ?? 'Sedang memproses...' }}</p>
              </div>
              <span class="item-badge shrink-0 ml-4 px-3 py-1 rounded-full text-xs font-medium inline-flex items-center gap-1.5
                @if ($item['status'] === 'success') bg-green-950/50 text-green-300 border border-green-900
                @elseif ($item['status'] === 'processing') bg-blue-950/50 text-blue-300 border border-blue-900
                @else bg-red-950/50 text-red-300 border border-red-900
                @endif">
                @if ($item['status'] === 'processing')
                  <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                @endif
                {{ $item['status'] === 'success' ? 'Sukses' : ($item['status'] === 'processing' ? 'Berjalan...' : 'Gagal') }}
              </span>
            </summary>
            <div class="border-t border-white/5 px-5 py-4">
              <p class="text-xs text-gray-500 mb-2">Log subprocess:</p>
              <pre class="item-log text-xs text-gray-400 font-mono whitespace-pre-wrap max-h-[80vh] overflow-y-auto bg-black/30 rounded-lg p-3">{{ $item['subprocess_log'] ?: '(kosong)' }}</pre>
            </div>
          </details>
        @endforeach
      </div>
    @endif
  </div>

  @if ($job && !$job['finished_at'])
    <script>
      // Job is still in flight — tail the currently-processing folder's
      // subprocess output live. Same relay + reconnect pattern as
      // translate.blade.php's ensureProgressConnection(): a same-origin
      // proxy (see TranslateController::log(), Controller::proxyDaemonSse),
      // named SSE events, and a needsReconnect + setInterval(5000) loop to
      // paper over the proxy's finite 55s timeout.
      const DAEMON_LOG_URL = "{{ route('translate.log') }}";

      let logSource = null;
      let needsReconnect = false;
      let currentFolderId = null;

      function ensureLogConnection() {
        if (logSource) return;

        logSource = new EventSource(DAEMON_LOG_URL);
        needsReconnect = false;

        logSource.addEventListener('start', (e) => {
          const data = JSON.parse(e.data);
          currentFolderId = data.folder_id;
          const details = document.querySelector(`details[data-folder-id="${currentFolderId}"]`);
          if (!details) return; // folder wasn't in the list rendered at page load
          details.open = true;
          details.querySelector('.item-badge').innerHTML =
            '<span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span> Berjalan...';
          details.querySelector('.item-message').textContent = 'Sedang memproses...';
          details.querySelector('.item-log').textContent = '';
        });

        logSource.addEventListener('line', (e) => {
          if (currentFolderId === null) return;
          const data = JSON.parse(e.data);
          const target = document.querySelector(`details[data-folder-id="${currentFolderId}"] .item-log`);
          if (!target) return;
          target.textContent += data.text;
          target.scrollTop = target.scrollHeight;
        });

        logSource.addEventListener('done', () => {
          logSource.close();
          logSource = null;
          location.reload();
        });

        logSource.addEventListener('error', () => {
          logSource.close();
          logSource = null;
          needsReconnect = true;
        });
      }

      ensureLogConnection();
      setInterval(() => {
        if (needsReconnect) ensureLogConnection();
      }, 5000);
    </script>
  @endif
@endsection
