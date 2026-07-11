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
          <details class="bg-gray-900 rounded-xl ring-1 ring-white/10 overflow-hidden group">
            <summary class="px-5 py-4 flex items-center justify-between cursor-pointer list-none">
              <div class="min-w-0">
                <p class="text-white font-medium truncate">{{ $item['folder_name'] ?? "folder #{$item['folder_id']}" }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $item['message'] }}</p>
              </div>
              <span class="shrink-0 ml-4 px-3 py-1 rounded-full text-xs font-medium
                {{ $item['status'] === 'success'
                    ? 'bg-green-950/50 text-green-300 border border-green-900'
                    : 'bg-red-950/50 text-red-300 border border-red-900' }}">
                {{ $item['status'] === 'success' ? 'Sukses' : 'Gagal' }}
              </span>
            </summary>
            <div class="border-t border-white/5 px-5 py-4">
              <p class="text-xs text-gray-500 mb-2">Log subprocess:</p>
              <pre class="text-xs text-gray-400 font-mono whitespace-pre-wrap max-h-96 overflow-y-auto bg-black/30 rounded-lg p-3">{{ $item['subprocess_log'] ?: '(kosong)' }}</pre>
            </div>
          </details>
        @endforeach
      </div>
    @endif
  </div>
@endsection
