@extends('layouts.app')

@section('title', 'Compare Duplicate')

@section('content')
  @php
    $today = now()->format('dmy');
    $suggestedTitle = ($candidate['name'] ?? '') . " ({$today})";
  @endphp

  @php
    // Badge styling per diff status computed backend-side in
    // DuplicateRepository.go (GetCandidateForCompare): "added" = only on the
    // incoming side, "removed" = only on the existing side, "changed" = same
    // filename different content hash, "unchanged" = same filename same hash.
    $statusStyle = [
      'added'     => ['ring-emerald-500/60', 'bg-emerald-950/80 text-emerald-300', 'baru'],
      'removed'   => ['ring-red-500/60', 'bg-red-950/80 text-red-300', 'hilang'],
      'changed'   => ['ring-amber-500/60', 'bg-amber-950/80 text-amber-300', 'beda'],
      'unchanged' => ['ring-white/5', 'bg-gray-800/80 text-gray-400', 'sama'],
    ];
  @endphp

  <div class="max-w-screen-xl mx-auto">
    <div class="mb-6">
      <a href="{{ route('duplicates') }}" class="text-sm text-indigo-400 hover:underline">&larr; Kembali ke daftar</a>
      <h1 class="text-2xl font-bold text-white tracking-tight mt-2">{{ $candidate['name'] ?? '' }}</h1>
    </div>

    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-400 mb-4">
      <span class="font-semibold text-gray-300">Legenda:</span>
      @foreach ($statusStyle as $status => [$ring, $badge, $label])
        <span class="inline-flex items-center gap-1.5">
          <span class="w-2.5 h-2.5 rounded-full ring-2 {{ $ring }}"></span>
          {{ ucfirst($label) }}
        </span>
      @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      <div class="bg-gray-900 rounded-xl ring-1 ring-white/10 p-4">
        <h2 class="text-sm font-semibold text-white mb-3">Lama (sudah tersimpan) &middot; {{ count($candidate['existing_pages'] ?? []) }} halaman</h2>
        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 max-h-[70vh] overflow-y-auto pr-1">
          @foreach (($candidate['existing_pages'] ?? []) as $page)
            @php [$ring, $badge, $label] = $statusStyle[$page['status'] ?? 'unchanged'] ?? $statusStyle['unchanged']; @endphp
            <a href="{{ same_origin_url($page['url']) }}" target="_blank" class="relative block rounded-lg overflow-hidden bg-gray-800 ring-2 {{ $ring }} hover:ring-indigo-500/60 transition">
              <div class="aspect-3/4 w-full overflow-hidden bg-gray-800">
                <x-thumbnail :src="$page['url']" :alt="$page['name']" class="w-full h-full object-cover" loading="lazy" />
              </div>
              <span class="absolute bottom-1 left-1 right-1 text-center text-[10px] font-medium rounded px-1 py-0.5 {{ $badge }}">{{ $label }}</span>
            </a>
          @endforeach
        </div>
      </div>

      <div class="bg-gray-900 rounded-xl ring-1 ring-white/10 p-4">
        <h2 class="text-sm font-semibold text-white mb-3">Baru (dari SRC_DIR) &middot; {{ count($candidate['incoming_pages'] ?? []) }} halaman</h2>
        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 max-h-[70vh] overflow-y-auto pr-1">
          @foreach (($candidate['incoming_pages'] ?? []) as $page)
            @php [$ring, $badge, $label] = $statusStyle[$page['status'] ?? 'unchanged'] ?? $statusStyle['unchanged']; @endphp
            <a href="{{ same_origin_url($page['url']) }}" target="_blank" class="relative block rounded-lg overflow-hidden bg-gray-800 ring-2 {{ $ring }} hover:ring-indigo-500/60 transition">
              <div class="aspect-3/4 w-full overflow-hidden bg-gray-800">
                <x-thumbnail :src="$page['url']" :alt="$page['name']" class="w-full h-full object-cover" loading="lazy" />
              </div>
              <span class="absolute bottom-1 left-1 right-1 text-center text-[10px] font-medium rounded px-1 py-0.5 {{ $badge }}">{{ $label }}</span>
            </a>
          @endforeach
        </div>
      </div>
    </div>

    <div id="resolveResult" class="hidden text-sm mb-4 p-3 rounded-lg"></div>

    <div class="bg-gray-900 rounded-xl ring-1 ring-white/10 p-5 space-y-5">
      <div>
        <h3 class="text-sm font-semibold text-white mb-2">Buat judul baru</h3>
        <p class="text-xs text-gray-500 mb-3">Simpan versi baru sebagai manga terpisah — kedua versi tetap ada.</p>
        <div class="flex gap-2">
          <input id="newTitleInput" type="text" value="{{ $suggestedTitle }}"
            class="flex-1 p-2.5 text-sm text-gray-100 border border-gray-700 rounded-lg bg-gray-950 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
          <button type="button" data-action="new_title"
            class="resolve-btn shrink-0 text-white bg-indigo-600 hover:bg-indigo-500 font-medium rounded-lg text-sm px-5 py-2.5 transition">
            Buat judul baru
          </button>
        </div>
      </div>

      <div class="border-t border-white/10 pt-5">
        <h3 class="text-sm font-semibold text-white mb-2">Merge</h3>
        <p class="text-xs text-gray-500 mb-3">Gabungkan ke manga yang sudah ada. Aksi ini mengubah folder yang sudah di-approve — tidak bisa dibatalkan.</p>
        <div class="flex flex-wrap gap-2">
          <button type="button" data-action="merge" data-merge-mode="replace"
            data-confirm="Folder lama akan dihapus total dan digantikan sepenuhnya oleh versi baru. Lanjutkan?"
            class="resolve-btn text-white bg-red-700 hover:bg-red-600 font-medium rounded-lg text-sm px-5 py-2.5 transition">
            Merge &mdash; pakai versi baru (replace)
          </button>
          <button type="button" data-action="merge" data-merge-mode="append"
            data-confirm="Halaman baru akan disambung sebagai halaman lanjutan (diberi nomor otomatis). Lanjutkan?"
            class="resolve-btn text-white bg-amber-700 hover:bg-amber-600 font-medium rounded-lg text-sm px-5 py-2.5 transition">
            Merge &mdash; gabung sebagai halaman lanjutan (append)
          </button>
        </div>
      </div>

      <div class="border-t border-white/10 pt-5">
        <h3 class="text-sm font-semibold text-white mb-2">Pakai versi lama</h3>
        <p class="text-xs text-gray-500 mb-3">Buang folder baru dari SRC_DIR sepenuhnya, versi lama yang sudah tersimpan tidak diubah sama sekali.</p>
        <button type="button" data-action="keep_existing"
          data-confirm="Folder baru dari SRC_DIR akan dihapus permanen, versi lama dipertahankan apa adanya. Lanjutkan?"
          class="resolve-btn text-white bg-gray-700 hover:bg-gray-600 font-medium rounded-lg text-sm px-5 py-2.5 transition">
          Pakai versi lama (buang versi baru)
        </button>
      </div>
    </div>
  </div>

  <script>
    const resolveUrl = '{{ route('duplicates.resolve', $candidate['id'] ?? 0) }}';
    const listUrl = '{{ route('duplicates') }}';
    const resultBox = document.getElementById('resolveResult');

    function showResult(ok, message) {
      resultBox.className = ok
        ? 'text-sm mb-4 p-3 rounded-lg bg-emerald-950/50 border border-emerald-900 text-emerald-300'
        : 'text-sm mb-4 p-3 rounded-lg bg-red-950/50 border border-red-900 text-red-300';
      resultBox.textContent = message;
      resultBox.classList.remove('hidden');
    }

    document.querySelectorAll('.resolve-btn').forEach(btn => {
      btn.addEventListener('click', async function() {
        const action = this.dataset.action;
        const confirmMsg = this.dataset.confirm;
        if (confirmMsg && !window.confirm(confirmMsg)) {
          return;
        }

        const payload = { action };
        if (action === 'new_title') {
          const title = document.getElementById('newTitleInput').value.trim();
          if (!title) {
            showResult(false, 'Judul baru tidak boleh kosong.');
            return;
          }
          payload.new_title = title;
        } else if (action === 'merge') {
          payload.merge_mode = this.dataset.mergeMode;
        }

        document.querySelectorAll('.resolve-btn').forEach(b => b.disabled = true);

        try {
          const res = await fetch(resolveUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
          });
          const body = await res.json();

          if (!res.ok) {
            showResult(false, body.Message || 'Gagal menyimpan resolusi.');
            document.querySelectorAll('.resolve-btn').forEach(b => b.disabled = false);
            return;
          }

          showResult(true, 'Berhasil diresolve. Kembali ke daftar...');
          setTimeout(() => { window.location.href = listUrl; }, 1000);
        } catch (e) {
          showResult(false, 'Gagal menghubungi server.');
          document.querySelectorAll('.resolve-btn').forEach(b => b.disabled = false);
        }
      });
    });
  </script>
@endsection
