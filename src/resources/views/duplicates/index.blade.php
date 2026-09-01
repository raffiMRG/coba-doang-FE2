@extends('layouts.app')

@section('title', 'Duplicates')

@section('content')
  <div class="max-w-screen-xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-white tracking-tight">Duplicates</h1>
        <p class="text-sm text-gray-400 mt-1">Folder yang namanya sama dengan manga yang sudah pernah di-approve — perlu direview manual.</p>
      </div>
      <button id="backfillBtn" type="button"
        class="text-white bg-gray-800 hover:bg-gray-700 ring-1 ring-white/10 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
        Cek duplikat existing
      </button>
    </div>

    <p id="backfillResult" class="hidden text-sm mb-6 p-3 rounded-lg"></p>

    @if ($error)
      <div class="flex items-center gap-3 p-4 rounded-lg bg-red-950/50 border border-red-900 text-red-300">
        {{ $error }}
      </div>
    @elseif (count($items) === 0)
      <p class="text-gray-500 text-center py-16">Tidak ada kandidat duplikat yang perlu direview saat ini.</p>
    @else
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($items as $item)
          <a href="{{ route('duplicates.show', $item['id']) }}"
            class="block p-4 bg-gray-900 rounded-xl ring-1 ring-white/10 hover:ring-indigo-500/60 transition">
            <div class="flex gap-3 mb-3">
              <div class="w-16 h-20 rounded-lg overflow-hidden bg-gray-800 shrink-0">
                <x-thumbnail :src="$item['existing_thumbnail']" :alt="$item['name']" />
              </div>
              <div class="min-w-0">
                <p class="text-sm font-semibold text-white leading-snug line-clamp-2">{{ $item['name'] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $item['created_at'] }}</p>
              </div>
            </div>
            <div class="flex items-center justify-center gap-2 text-xs font-medium">
              <span class="px-2 py-1 rounded-md bg-gray-800 text-gray-300">{{ $item['existing_page_count'] }} halaman lama</span>
              <span class="text-gray-600">&rarr;</span>
              <span class="px-2 py-1 rounded-md bg-indigo-950 text-indigo-300">{{ $item['incoming_page_count'] }} halaman baru</span>
            </div>
          </a>
        @endforeach
      </div>
    @endif
  </div>

  <script>
    document.getElementById('backfillBtn').addEventListener('click', async function() {
      const btn = this;
      const result = document.getElementById('backfillResult');
      btn.disabled = true;
      btn.textContent = 'Memeriksa...';

      try {
        const res = await fetch('{{ route('duplicates.backfill') }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
          },
        });
        const body = await res.json();

        if (!res.ok) {
          result.className = 'text-sm mb-6 p-3 rounded-lg bg-red-950/50 border border-red-900 text-red-300';
          result.textContent = body.Message || 'Gagal menjalankan pengecekan.';
        } else {
          const data = body.Data || {};
          const converted = data.converted || 0;
          const errors = data.errors || [];
          result.className = 'text-sm mb-6 p-3 rounded-lg bg-gray-900 ring-1 ring-white/10 text-gray-300';
          result.textContent = `Ditemukan ${converted} duplikat baru dari data lama.` +
            (errors.length ? ` ${errors.length} nama dilewati (folder tidak ditemukan di disk).` : '');
          if (converted > 0) {
            setTimeout(() => window.location.reload(), 1200);
          }
        }
        result.classList.remove('hidden');
      } catch (e) {
        result.className = 'text-sm mb-6 p-3 rounded-lg bg-red-950/50 border border-red-900 text-red-300';
        result.textContent = 'Gagal menghubungi server.';
        result.classList.remove('hidden');
      } finally {
        btn.disabled = false;
        btn.textContent = 'Cek duplikat existing';
      }
    });
  </script>
@endsection
