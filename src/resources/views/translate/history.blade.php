@extends('layouts.app')

@section('title', 'Riwayat Translate')

@section('content')
  <div class="max-w-screen-xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold text-white tracking-tight">Riwayat Translate</h1>
      <a href="{{ route('translate') }}" class="text-sm text-indigo-400 hover:underline">&larr; Kembali</a>
    </div>

    @if ($error)
      <div class="flex items-center gap-3 p-4 rounded-lg bg-red-950/50 border border-red-900 text-red-300">
        {{ $error }}
      </div>
    @elseif (count($jobs) === 0)
      <p class="text-gray-500 text-center py-16">Belum ada riwayat job translate.</p>
    @else
      <div class="bg-gray-900 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <table class="w-full text-sm text-left">
          <thead class="bg-gray-800/50 text-gray-400 text-xs uppercase">
            <tr>
              <th class="px-5 py-3">Job</th>
              <th class="px-5 py-3">Mulai</th>
              <th class="px-5 py-3">Selesai</th>
              <th class="px-5 py-3">Total</th>
              <th class="px-5 py-3">Sukses</th>
              <th class="px-5 py-3">Gagal</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/5">
            @foreach ($jobs as $job)
              <tr class="hover:bg-gray-800/40 transition cursor-pointer"
                onclick="window.location='{{ route('translate.history.show', $job['id']) }}'">
                <td class="px-5 py-3 text-white font-medium">#{{ $job['id'] }}</td>
                <td class="px-5 py-3 text-gray-400">{{ $job['started_at'] }}</td>
                <td class="px-5 py-3 text-gray-400">{{ $job['finished_at'] ?? 'Sedang berjalan...' }}</td>
                <td class="px-5 py-3 text-gray-400">{{ $job['total'] }}</td>
                <td class="px-5 py-3">
                  <span class="text-green-400 font-semibold">{{ $job['success_count'] }}</span>
                </td>
                <td class="px-5 py-3">
                  @if ($job['failed_count'] > 0)
                    <span class="text-red-400 font-semibold">{{ $job['failed_count'] }}</span>
                  @else
                    <span class="text-gray-500">0</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
@endsection
