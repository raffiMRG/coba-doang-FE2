@extends('layouts.app')

@section('title', 'Laporan Bug')

@section('content')
  <div class="max-w-screen-xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold text-white tracking-tight">Laporan Bug</h1>
    </div>

    @php
      $statusTabs = ['all' => 'Semua', 'open' => 'Open', 'fixed' => 'Fixed'];
      $tabUrl = fn(string $s) => request()->fullUrlWithQuery(['status' => $s]);
      $sortUrl = fn(string $s) => request()->fullUrlWithQuery(['sort' => $s]);
    @endphp

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div class="flex items-center gap-1.5">
        @foreach ($statusTabs as $value => $label)
          <a href="{{ $tabUrl($value) }}"
            class="px-3 py-1.5 rounded-lg text-sm font-medium transition {{ $status === $value ? 'text-white bg-indigo-600' : 'text-gray-400 bg-gray-900 hover:bg-gray-800 hover:text-white' }}">
            {{ $label }}
          </a>
        @endforeach
      </div>

      <div class="flex items-center gap-1.5 text-sm">
        <span class="text-gray-500">Urutkan:</span>
        <a href="{{ $sortUrl('newest') }}"
          class="px-3 py-1.5 rounded-lg font-medium transition {{ $sort === 'newest' ? 'text-white bg-indigo-600' : 'text-gray-400 bg-gray-900 hover:bg-gray-800 hover:text-white' }}">
          Terbaru
        </a>
        <a href="{{ $sortUrl('oldest') }}"
          class="px-3 py-1.5 rounded-lg font-medium transition {{ $sort === 'oldest' ? 'text-white bg-indigo-600' : 'text-gray-400 bg-gray-900 hover:bg-gray-800 hover:text-white' }}">
          Terlama
        </a>
      </div>
    </div>

    @if ($error)
      <div class="flex items-center gap-3 p-4 rounded-lg bg-red-950/50 border border-red-900 text-red-300">
        {{ $error }}
      </div>
    @elseif (count($items) === 0)
      <p class="text-gray-500 text-center py-16">Belum ada laporan bug.</p>
    @else
      <div class="bg-gray-900 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <table class="w-full text-sm text-left">
          <thead class="bg-gray-800/50 text-gray-400 text-xs uppercase">
            <tr>
              <th class="px-5 py-3">Manga</th>
              <th class="px-5 py-3">Deskripsi</th>
              <th class="px-5 py-3">Dilaporkan</th>
              <th class="px-5 py-3">Status</th>
              <th class="px-5 py-3">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/5">
            @foreach ($items as $item)
              <tr class="hover:bg-gray-800/40 transition" data-report-row data-id="{{ $item['id'] }}">
                <td class="px-5 py-3">
                  <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-14 shrink-0 rounded overflow-hidden bg-gray-800">
                      <x-thumbnail :src="$item['thumbnail']" :alt="$item['folder_name']" class="w-full h-full object-cover" />
                    </div>
                    <span class="text-white font-medium truncate max-w-[16rem]">{{ $item['folder_name'] }}</span>
                  </div>
                </td>
                <td class="px-5 py-3 text-gray-400 max-w-sm">
                  <p class="line-clamp-2">{{ $item['description'] }}</p>
                </td>
                <td class="px-5 py-3 text-gray-400 whitespace-nowrap">{{ $item['created_at'] }}</td>
                <td class="px-5 py-3">
                  <span data-status-badge
                    class="px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $item['status'] === 'fixed' ? 'bg-green-950/50 text-green-300 border border-green-900' : 'bg-yellow-950/50 text-yellow-300 border border-yellow-900' }}">
                    {{ $item['status'] === 'fixed' ? 'Fixed' : 'Open' }}
                  </span>
                </td>
                <td class="px-5 py-3">
                  <div class="flex items-center gap-2">
                    <a href="/id/{{ $item['folder_id'] }}"
                      class="text-xs font-medium text-indigo-400 hover:underline whitespace-nowrap">
                      Buka Manga
                    </a>
                    <button type="button" data-toggle-status data-current-status="{{ $item['status'] }}"
                      class="text-xs font-medium text-gray-400 hover:text-white whitespace-nowrap">
                      {{ $item['status'] === 'fixed' ? 'Buka Lagi' : 'Tandai Selesai' }}
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <script>
    function getXsrfToken() {
      return decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] || '');
    }

    document.querySelectorAll('[data-toggle-status]').forEach(btn => {
      btn.addEventListener('click', async () => {
        const row = btn.closest('[data-report-row]');
        const id = row.dataset.id;
        const current = btn.dataset.currentStatus;
        const next = current === 'fixed' ? 'open' : 'fixed';

        try {
          const res = await fetch(`/bug-reports/${id}/status`, {
            method: 'PATCH',
            headers: {
              'Content-Type': 'application/json',
              'X-XSRF-TOKEN': getXsrfToken(),
            },
            body: JSON.stringify({ status: next }),
          });
          const result = await res.json();
          if (!res.ok) throw new Error(result.Message || 'Gagal mengubah status');

          btn.dataset.currentStatus = next;
          btn.textContent = next === 'fixed' ? 'Buka Lagi' : 'Tandai Selesai';

          const badge = row.querySelector('[data-status-badge]');
          badge.textContent = next === 'fixed' ? 'Fixed' : 'Open';
          badge.className = next === 'fixed'
            ? 'px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap bg-green-950/50 text-green-300 border border-green-900'
            : 'px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap bg-yellow-950/50 text-yellow-300 border border-yellow-900';
        } catch (err) {
          alert('Error: ' + err.message);
        }
      });
    });
  </script>
@endsection
