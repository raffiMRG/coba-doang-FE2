@extends('layouts.app')

@section('title', 'Folder List')

@section('content')

    <h1 class="text-2xl font-bold text-white tracking-tight mb-6">Status</h1>

    @if ($error)
        <div class="flex items-center gap-3 p-4 rounded-lg bg-red-950/50 border border-red-900 text-red-300">
            {{ $error }}
        </div>
    @else
        <div id="alert-4"
            class="hidden fixed top-3 right-6 items-center p-4 mb-4 rounded-lg bg-amber-950/90 backdrop-blur border border-amber-900 text-amber-300 shadow-lg z-50"
            role="alert">
            <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                Pilih minimal 1 sebelum melanjutkan.
            </div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-amber-950 text-amber-400 rounded-lg focus:ring-2 focus:ring-amber-700 p-1.5 hover:bg-amber-900 inline-flex items-center justify-center h-8 w-8"
                data-dismiss-target="#alert-4" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>

        <!-- Progress Box (hidden by default) -->
        <div id="progressBox"
            class="hidden fixed bottom-24 right-6 bg-gray-900 border border-gray-800 p-4 rounded-xl shadow-xl text-sm w-72 z-50">
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-semibold text-white">Memindahkan folder</h4>
                <span id="progressPercent" class="text-indigo-400 font-semibold text-xs">0%</span>
            </div>
            <div class="w-full h-2 bg-gray-800 rounded-full overflow-hidden">
                <div id="progressBar" class="h-full bg-indigo-500 transition-all duration-300 ease-out"
                    style="width: 0%"></div>
            </div>
            <div id="progressStatus" class="mt-2 text-xs text-gray-400">Memulai...</div>
        </div>

        <!-- Folder List with Checkboxes -->

        @if (count($folders) === 0)
            <p class="text-gray-500 text-center py-16">Tidak ada folder untuk dipindahkan.</p>
        @else
            <ul class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($folders as $folder)
                    <li>
                        <label
                            class="group relative block rounded-xl overflow-hidden bg-gray-900 ring-1 ring-white/10 cursor-pointer transition hover:-translate-y-1 hover:ring-indigo-500/60 has-checked:ring-2 has-checked:ring-indigo-500">
                            <input type="checkbox" id="{{ $folder['id'] }}-option" value="{{ $folder['id'] }}"
                                class="sr-only">
                            <div class="aspect-3/4 w-full overflow-hidden bg-gray-800">
                                <x-thumbnail :src="$folder['thumbnail']" :alt="$folder['name']"
                                    class="w-full h-full object-cover transition duration-300 group-hover:scale-105" />
                            </div>
                            <div
                                class="absolute inset-x-0 bottom-0 bg-linear-to-t from-gray-950 via-gray-950/80 to-transparent px-3 pt-8 pb-3">
                                <p class="text-sm font-semibold text-white leading-snug line-clamp-2">
                                    {{ $folder['name'] }}
                                </p>
                            </div>
                            <div
                                class="absolute top-2 right-2 w-6 h-6 rounded-full border-2 border-white/70 bg-gray-950/40 backdrop-blur flex items-center justify-center transition group-has-checked:bg-indigo-600 group-has-checked:border-indigo-500">
                                <svg class="w-4 h-4 text-white opacity-0 transition group-has-checked:opacity-100"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.7 5.3a1 1 0 0 1 0 1.4l-8 8a1 1 0 0 1-1.4 0l-4-4a1 1 0 1 1 1.4-1.4L8 12.6l7.3-7.3a1 1 0 0 1 1.4 0Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </label>
                    </li>
                @endforeach
            </ul>

            <x-pagination :page="$page" :pages="$pages" :base-url="$baseUrl" />
        @endif

        <!-- Floating Action Button -->
        <button id="sendBtn" type="button"
            class="fixed bottom-6 right-6 text-white bg-indigo-600 hover:bg-indigo-500 focus:ring-4 focus:outline-none focus:ring-indigo-800 font-medium rounded-full text-sm p-4 text-center inline-flex items-center shadow-lg shadow-indigo-950/50 transition">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 5h12m0 0L9 1m4 4L9 9" />
            </svg>
        </button>

        {{-- <script>
            document.getElementById('sendBtn').addEventListener('click', async () => {
                const checked = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'))
                    .map(cb => parseInt(cb.value));

                if (checked.length === 0) {
                    document.getElementById('alert-4').classList.remove('hidden');
                    return;
                }

                try {
                    console.log(checked);
                    const API_URL = "{{ config('app.api_url') }}";

                    const res = await fetch(API_URL + '/folders', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: checked
                        })
                    });

                    if (!res.ok) throw new Error('Gagal mengirim data');
                    alert('Data berhasil dikirim!');
                } catch (err) {
                    alert('Error bro: ' + err.message);
                }
            });
        </script> --}}
        <script>
            document.getElementById('sendBtn').addEventListener('click', async () => {
                const checked = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'))
                    .map(cb => parseInt(cb.value));

                if (checked.length === 0) {
                    document.getElementById('alert-4').classList.remove('hidden');
                    return;
                }

                // const progressBox = document.getElementById('progressBox') || createProgressBox();
                // const progressBox = createProgressBox();
                const progressBox = document.getElementById('progressBox');

                try {
                    console.log('Mengirim data:', checked);

                    // 1️⃣ Kirim perintah untuk mulai proses pemindahan — lewat
                    // Laravel (bukan langsung ke backend Go), karena browser
                    // tidak punya akses ke access_token yang tersimpan di session.
                    const res = await fetch('/status/move', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-XSRF-TOKEN': decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] || '')
                        },
                        body: JSON.stringify({
                            Id: checked
                        })
                    });

                    if (!res.ok) throw new Error('Gagal memulai proses pemindahan');

                    const result = await res.json();
                    console.log('Hasilnya:', result);
                    const taskID = result?.Data?.task_id; // ambil taskID dari respons backend

                    console.log('Task ID:', taskID);

                    // if (!taskID) throw new Error('Task ID tidak ditemukan di respons backend');

                    if (!taskID) {
                        log('Task ID tidak ditemukan di respons backend');
                        return;
                    }
                    // 2️⃣ Mulai dengarkan SSE untuk progres dengan taskID — juga
                    // lewat Laravel: EventSource tidak bisa kirim Authorization
                    // header sama sekali, jadi tidak bisa langsung ke backend.
                    listenProgress(`/status/progress/${taskID}`, progressBox, checked.length);

                } catch (err) {
                    alert('Error: ' + err.message);
                }
            });

            /**
             * Fungsi untuk membuka koneksi SSE dan menampilkan determinate
             * progress bar. Backend cuma kirim persentase mentah (0-100, satu
             * tick per folder selesai) — jumlah folder (total) sudah diketahui
             * di client, jadi hitungan "X dari Y" dihitung di sini.
             */
            function listenProgress(url, progressBox, total) {
                const bar = progressBox.querySelector('#progressBar');
                const percentLabel = progressBox.querySelector('#progressPercent');
                const status = progressBox.querySelector('#progressStatus');

                progressBox.classList.remove('hidden');
                bar.classList.remove('bg-red-500');
                bar.classList.add('bg-indigo-500');
                bar.style.width = '0%';
                percentLabel.textContent = '0%';
                status.textContent = 'Memulai...';

                const evtSource = new EventSource(url);

                evtSource.addEventListener('progress', (e) => {
                    const pct = parseFloat(e.data);
                    const done = Math.round((pct / 100) * total);
                    bar.style.width = `${pct}%`;
                    percentLabel.textContent = `${Math.round(pct)}%`;
                    status.textContent = `${done} dari ${total} folder dipindahkan`;
                });

                evtSource.addEventListener('done', () => {
                    bar.style.width = '100%';
                    percentLabel.textContent = '100%';
                    status.textContent = `✅ ${total} folder selesai dipindahkan`;
                    evtSource.close();
                    setTimeout(() => progressBox.classList.add('hidden'), 4000);
                });

                evtSource.addEventListener('error', () => {
                    bar.classList.remove('bg-indigo-500');
                    bar.classList.add('bg-red-500');
                    status.textContent = '⚠️ Terjadi kesalahan koneksi SSE.';
                    evtSource.close();
                });
            }

            /**
             * Fungsi untuk membuat UI progres jika belum ada.
             */
            function createProgressBox() {
                const box = document.createElement('div');
                box.id = 'progressBox';
                box.className = 'fixed bottom-24 right-6 bg-gray-900 border border-gray-800 p-4 rounded-xl shadow-xl text-sm w-72 hidden z-50';
                box.innerHTML = `
        <div class="flex items-center justify-between mb-2">
            <h4 class="font-semibold text-white">Memindahkan folder</h4>
            <span id="progressPercent" class="text-indigo-400 font-semibold text-xs">0%</span>
        </div>
        <div class="w-full h-2 bg-gray-800 rounded-full overflow-hidden">
            <div id="progressBar" class="h-full bg-indigo-500 transition-all duration-300 ease-out" style="width: 0%"></div>
        </div>
        <div id="progressStatus" class="mt-2 text-xs text-gray-400">Memulai...</div>
    `;
                document.body.appendChild(box);
                return box;
            }
        </script>


    @endif

@endsection
