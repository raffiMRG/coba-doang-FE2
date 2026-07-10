@extends('layouts.app')

@section('title', 'Extract')

@section('content')
  <div class="max-w-screen-md mx-auto">
    <h1 class="mb-6 text-2xl font-bold text-white tracking-tight">Extract Zip</h1>

    <div class="mb-6 p-5 bg-gray-900 rounded-xl ring-1 ring-white/10 flex items-center justify-between">
      <div>
        <h2 class="text-lg font-semibold text-white">Worker</h2>
        <p class="text-sm text-gray-400">Daemon lokal yang menjalankan proses extract di laptop kamu.</p>
      </div>
      <span id="workerBadge"
        class="px-3 py-1 rounded-full text-xs font-medium bg-gray-800 text-gray-400 border border-gray-700">
        Checking...
      </span>
    </div>

    <div class="mb-6 p-5 bg-gray-900 rounded-xl ring-1 ring-white/10">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-lg font-semibold text-white">1. Scan Zip</h2>
        <button id="scanBtn" type="button" disabled
          class="text-white bg-indigo-600 hover:bg-indigo-500 focus:ring-4 focus:ring-indigo-800 disabled:opacity-40 disabled:cursor-not-allowed font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
          Scan Zip
        </button>
      </div>
      <ul id="zipList" class="text-sm text-gray-400 space-y-1 list-disc list-inside"></ul>
    </div>

    <div class="p-5 bg-gray-900 rounded-xl ring-1 ring-white/10">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-lg font-semibold text-white">2. Start Process</h2>
        <button id="startBtn" type="button" disabled
          class="text-white bg-indigo-600 hover:bg-indigo-500 focus:ring-4 focus:ring-indigo-800 disabled:opacity-40 disabled:cursor-not-allowed font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
          Start Process
        </button>
      </div>

      <div id="progressBox" class="hidden">
        <div class="flex items-center justify-between mb-2">
          <span id="progressStatus" class="text-sm text-gray-400">Memulai...</span>
          <span id="progressPercent" class="text-indigo-400 font-semibold text-xs">0%</span>
        </div>
        <div class="w-full h-2 bg-gray-800 rounded-full overflow-hidden mb-3">
          <div id="progressBar" class="h-full bg-indigo-500 transition-all duration-300 ease-out" style="width: 0%"></div>
        </div>
        <ul id="progressLog" class="text-xs text-gray-500 space-y-1"></ul>
      </div>
    </div>
  </div>

  <script>
    const DAEMON_URL = "{{ config('app.extract_daemon_url') }}";

    const workerBadge = document.getElementById('workerBadge');
    const scanBtn = document.getElementById('scanBtn');
    const startBtn = document.getElementById('startBtn');
    const zipList = document.getElementById('zipList');
    const progressBox = document.getElementById('progressBox');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const progressStatus = document.getElementById('progressStatus');
    const progressLog = document.getElementById('progressLog');

    let online = false;
    let processing = false;
    let scannedCount = 0;
    let evtSource = null;
    let needsReconnect = false; // true only after a genuine connection error, never after a clean "done"

    function updateButtons() {
      scanBtn.disabled = !online || processing;
      startBtn.disabled = !online || processing || scannedCount === 0;
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

    scanBtn.addEventListener('click', async () => {
      scanBtn.disabled = true;
      try {
        const res = await fetch(DAEMON_URL + '/scan');
        const data = await res.json();
        if (!res.ok) throw new Error(data.error || 'Gagal scan zip');

        scannedCount = data.count;
        zipList.innerHTML = '';
        if (data.zips.length === 0) {
            const li = document.createElement('li');
            li.textContent = 'Tidak ada file zip ditemukan.';
            zipList.appendChild(li);
        } else {
            data.zips.forEach(name => {
                const li = document.createElement('li');
                li.textContent = name;
                zipList.appendChild(li);
            });
        }
      } catch (err) {
        alert('Error: ' + err.message);
      }
      updateButtons();
    });

    // /progress now replays the full history of the current/last job as
    // soon as you connect (backfill), then streams live — so one persistent
    // connection, opened as soon as the page loads rather than only after
    // clicking Start, is enough to "resume" showing an in-progress or just-
    // finished run after a reload, a closed-then-reopened tab, or coming
    // back from another page.
    function ensureProgressConnection() {
      if (evtSource) return;

      // Every new connection gets a full backfill from index 1, even if the
      // job already finished (so a reload/reopen after completion still
      // shows the result) — so the log/bar must start clean here too, not
      // just when the Start button is clicked, or a reconnect would append
      // the same already-shown history on top of itself.
      progressLog.innerHTML = '';
      progressBar.style.width = '0%';
      progressPercent.textContent = '0%';

      evtSource = new EventSource(DAEMON_URL + '/progress');
      needsReconnect = false;

      evtSource.addEventListener('progress', (e) => {
        const evt = JSON.parse(e.data);
        processing = true;
        progressBox.classList.remove('hidden');
        const pct = (evt.index / evt.total) * 100;
        progressBar.style.width = `${pct}%`;
        progressPercent.textContent = `${Math.round(pct)}%`;
        progressStatus.textContent = `${evt.index} dari ${evt.total} diproses`;

        const li = document.createElement('li');
        const icon = evt.status === 'success' ? '✅' : '⚠️';
        li.textContent = `${icon} ${evt.zip_name} — ${evt.message}`;
        progressLog.appendChild(li);
        updateButtons();
      });

      evtSource.addEventListener('done', () => {
        progressStatus.textContent = 'Selesai.';
        evtSource.close();
        evtSource = null;
        processing = false;
        scannedCount = 0;
        zipList.innerHTML = '';
        updateButtons();
      });

      evtSource.addEventListener('error', () => {
        progressStatus.textContent = '⚠️ Terjadi kesalahan koneksi.';
        evtSource.close();
        evtSource = null;
        needsReconnect = true;
        processing = false;
        updateButtons();
      });
    }

    startBtn.addEventListener('click', async () => {
      processing = true;
      updateButtons();

      progressBox.classList.remove('hidden');
      progressBar.style.width = '0%';
      progressPercent.textContent = '0%';
      progressStatus.textContent = 'Memulai...';
      progressLog.innerHTML = '';

      try {
        const res = await fetch(DAEMON_URL + '/start', { method: 'POST' });
        const data = await res.json();
        if (!res.ok) throw new Error(data.error || 'Gagal memulai proses');
      } catch (err) {
        alert('Error: ' + err.message);
        processing = false;
        updateButtons();
        return;
      }

      ensureProgressConnection();
    });

    ping();
    ensureProgressConnection();
    // Reconnect automatically on the next tick only after a real connection
    // error (needsReconnect) — not unconditionally, since /progress always
    // backfills the full history of the current/last job on every connect;
    // reconnecting on a fixed timer even when nothing changed would just
    // redraw the same already-finished job's log over and over forever.
    setInterval(() => {
      ping();
      if (needsReconnect) ensureProgressConnection();
    }, 5000);
  </script>
@endsection
