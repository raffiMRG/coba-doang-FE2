@extends('layouts.app')

@section('title', 'Translate')

@section('content')
  <div class="max-w-screen-xl mx-auto">
    <h1 class="mb-6 text-2xl font-bold text-white tracking-tight">Translate</h1>

    @if ($error)
      <div class="flex items-center gap-3 p-4 rounded-lg bg-red-950/50 border border-red-900 text-red-300">
        {{ $error }}
      </div>
    @else
      <div class="mb-6 p-5 bg-gray-900 rounded-xl ring-1 ring-white/10 flex items-center justify-between">
        <div>
          <h2 class="text-lg font-semibold text-white">Worker</h2>
          <p class="text-sm text-gray-400">Daemon lokal (manga-image-translator) yang menjalankan proses translate di laptop kamu.</p>
        </div>
        <span id="workerBadge"
          class="px-3 py-1 rounded-full text-xs font-medium bg-gray-800 text-gray-400 border border-gray-700">
          Checking...
        </span>
      </div>

      @if (count($items) === 0)
        <p class="text-gray-500 text-center py-16">Tidak ada manga yang menunggu untuk di-translate.</p>
      @else
        <div class="mb-6 p-5 bg-gray-900 rounded-xl ring-1 ring-white/10">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Antrian Translate ({{ count($items) }})</h2>
            <button id="startBtn" type="button" disabled
              class="text-white bg-indigo-600 hover:bg-indigo-500 focus:ring-4 focus:ring-indigo-800 disabled:opacity-40 disabled:cursor-not-allowed font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
              Start Batch
            </button>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach ($items as $item)
              <label
                class="group relative block rounded-xl overflow-hidden bg-gray-900 ring-1 ring-white/10 cursor-pointer transition hover:-translate-y-1 hover:ring-indigo-500/60 has-checked:ring-2 has-checked:ring-indigo-500">
                <input type="checkbox" value="{{ $item['folder_id'] }}" class="sr-only translate-checkbox">
                <div class="aspect-3/4 w-full overflow-hidden bg-gray-800">
                  <img src="https://i.imgur.com/TNOs1Xx.png" class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                </div>
                <div class="absolute inset-x-0 bottom-0 bg-linear-to-t from-gray-950 via-gray-950/80 to-transparent px-3 pt-8 pb-3">
                  <p class="text-sm font-semibold text-white leading-snug line-clamp-2">{{ $item['name'] }}</p>
                </div>
              </label>
            @endforeach
          </div>
        </div>

        <div id="progressBox" class="hidden p-5 bg-gray-900 rounded-xl ring-1 ring-white/10">
          <div class="flex items-center justify-between mb-2">
            <span id="progressStatus" class="text-sm text-gray-400">Memulai...</span>
            <span id="progressPercent" class="text-indigo-400 font-semibold text-xs">0%</span>
          </div>
          <div class="w-full h-2 bg-gray-800 rounded-full overflow-hidden mb-3">
            <div id="progressBar" class="h-full bg-indigo-500 transition-all duration-300 ease-out" style="width: 0%"></div>
          </div>
          <ul id="progressLog" class="text-xs text-gray-500 space-y-1"></ul>
        </div>
      @endif
    @endif
  </div>

  <script>
    const DAEMON_URL = "{{ config('app.translate_daemon_url') }}";

    const workerBadge = document.getElementById('workerBadge');
    const startBtn = document.getElementById('startBtn');
    const progressBox = document.getElementById('progressBox');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const progressStatus = document.getElementById('progressStatus');
    const progressLog = document.getElementById('progressLog');

    let online = false;
    let processing = false;

    function checkedIds() {
      return Array.from(document.querySelectorAll('.translate-checkbox:checked')).map(cb => parseInt(cb.value));
    }

    function updateButtons() {
      if (!startBtn) return;
      startBtn.disabled = !online || processing || checkedIds().length === 0;
    }

    async function ping() {
      try {
        const res = await fetch(DAEMON_URL + '/ping');
        online = res.ok;
      } catch (err) {
        online = false;
      }
      workerBadge.textContent = online ? 'Worker online' : 'Worker offline';
      workerBadge.className = online
        ? 'px-3 py-1 rounded-full text-xs font-medium bg-green-950/50 text-green-300 border border-green-900'
        : 'px-3 py-1 rounded-full text-xs font-medium bg-red-950/50 text-red-300 border border-red-900';
      updateButtons();
    }

    document.querySelectorAll('.translate-checkbox').forEach(cb => cb.addEventListener('change', updateButtons));

    if (startBtn) {
      startBtn.addEventListener('click', async () => {
        const ids = checkedIds();
        if (ids.length === 0) return;

        processing = true;
        updateButtons();

        progressBox.classList.remove('hidden');
        progressBar.style.width = '0%';
        progressPercent.textContent = '0%';
        progressStatus.textContent = 'Memulai...';
        progressLog.innerHTML = '';

        try {
          const res = await fetch(DAEMON_URL + '/start', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ folder_ids: ids }),
          });
          const data = await res.json();
          if (!res.ok) throw new Error(data.detail || 'Gagal memulai proses');
        } catch (err) {
          alert('Error: ' + err.message);
          processing = false;
          updateButtons();
          return;
        }

        const evtSource = new EventSource(DAEMON_URL + '/progress');

        evtSource.addEventListener('progress', (e) => {
          const evt = JSON.parse(e.data);
          const pct = (evt.index / evt.total) * 100;
          progressBar.style.width = `${pct}%`;
          progressPercent.textContent = `${Math.round(pct)}%`;
          progressStatus.textContent = `${evt.index} dari ${evt.total} diproses`;

          const li = document.createElement('li');
          const icon = evt.status === 'success' ? '✅' : '⚠️';
          li.textContent = `${icon} ${evt.name || evt.folder_id} — ${evt.message}`;
          progressLog.appendChild(li);
        });

        evtSource.addEventListener('done', () => {
          progressStatus.textContent = 'Selesai. Reload halaman untuk melihat antrian terbaru.';
          evtSource.close();
          processing = false;
          updateButtons();
        });

        evtSource.addEventListener('error', () => {
          progressStatus.textContent = '⚠️ Terjadi kesalahan koneksi.';
          evtSource.close();
          processing = false;
          updateButtons();
        });
      });
    }

    ping();
    setInterval(ping, 5000);
  </script>
@endsection
